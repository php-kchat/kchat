(function () {
    var scriptTag = document.currentScript || (function () {
        var scripts = document.getElementsByTagName('script');
        return scripts[scripts.length - 1];
    })();

    var scriptSrc = scriptTag && scriptTag.src ? scriptTag.src : '';
    var token = '';

    if (scriptSrc) {
        var query = new URL(scriptSrc).searchParams;
        token = query.get('token') || '';
    }

    if (!token) {
        return;
    }

    var apiBase = scriptSrc ? scriptSrc.replace(/\/widget\/embed\.js.*$/, '') : window.location.origin;
    var apiBasePath = apiBase + '/api';
    var storageKey = 'kchat_widget_' + (token || 'default');
    var sessionId = null;
    var lastMessageId = 0;
    var visitorUid = 'visitor_' + (Date.now().toString(36) + Math.random().toString(36).slice(2, 10));
    var visitorName = 'Visitor';
    var widgetConfig = null;

    try {
        var savedVisitorUid = localStorage.getItem(storageKey + ':visitor_uid');
        if (savedVisitorUid) {
            visitorUid = savedVisitorUid;
        } else {
            localStorage.setItem(storageKey + ':visitor_uid', visitorUid);
        }

        var savedSessionId = localStorage.getItem(storageKey + ':session_id');
        if (savedSessionId) {
            sessionId = savedSessionId;
        }
    } catch (e) {
        // Ignore storage issues in restricted browser contexts.
    }

    function csrfToken() {
        var el = document.querySelector('meta[name="csrf-token"]');
        return el ? el.getAttribute('content') : '';
    }

    function requestJSON(url, payload) {
        var headers = {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        };

        if (csrfToken()) {
            headers['X-CSRF-TOKEN'] = csrfToken();
        }

        return fetch(url, {
            method: 'POST',
            headers: headers,
            body: new URLSearchParams(payload).toString()
        }).then(function (response) {
            return response.clone().json().catch(function () {
                return response.text();
            });
        });
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function createWidget() {
        var container = document.createElement('div');
        container.id = 'kchat-widget-root';
        container.style.position = 'fixed';
        container.style.zIndex = '2147483647';
        container.style.bottom = '20px';
        container.style.right = '20px';
        container.style.fontFamily = 'Arial, sans-serif';

        var launcher = document.createElement('button');
        launcher.type = 'button';
        launcher.id = 'kchat-widget-launcher';
        launcher.style.border = 'none';
        launcher.style.borderRadius = '50%';
        launcher.style.width = '62px';
        launcher.style.height = '62px';
        launcher.style.background = widgetConfig.color || '#007bff';
        launcher.style.color = '#fff';
        launcher.style.boxShadow = '0 12px 30px rgba(0,0,0,0.18)';
        launcher.style.cursor = 'pointer';
        launcher.style.fontSize = '28px';
        launcher.title = widgetConfig.title || 'Chat with us';
        launcher.innerHTML = '<i class="fa ' + (widgetConfig.icon || 'fa-comments') + '"></i>';

        var panel = document.createElement('div');
        panel.id = 'kchat-widget-panel';
        panel.style.display = 'none';
        panel.style.width = '360px';
        panel.style.maxWidth = '92vw';
        panel.style.background = '#fff';
        panel.style.border = '1px solid rgba(0,0,0,0.08)';
        panel.style.borderRadius = '16px';
        panel.style.boxShadow = '0 18px 40px rgba(0,0,0,0.18)';
        panel.style.overflow = 'hidden';
        panel.style.marginBottom = '18px';
        panel.style.position = 'relative';

        panel.innerHTML = [
            '<div style="padding: 14px 16px; background: ' + (widgetConfig.color || '#007bff') + '; color: #fff; display: flex; align-items: center; justify-content: space-between;">',
            '  <div style="display:flex; align-items:center; gap:10px; font-weight:700;">',
            '    <span style="display:inline-flex; width:30px; height:30px; border-radius:50%; background: rgba(255,255,255,0.18); align-items:center; justify-content:center;"><i class="fa ' + (widgetConfig.icon || 'fa-comments') + '"></i></span>',
            '    <span>' + escapeHtml(widgetConfig.title || 'Chat with us') + '</span>',
            '  </div>',
            '  <button type="button" id="kchat-widget-close" style="background:transparent; border:none; color:#fff; font-size:24px; cursor:pointer;">×</button>',
            '</div>',
            '<div id="kchat-widget-body" style="height: 330px; display:flex; flex-direction:column; background:#f8f9fb;">',
            '  <div id="kchat-widget-messages" style="flex:1; overflow:auto; padding:12px; font-size:14px; line-height:1.5; color:#212529;">',
            '    <div style="padding:16px; color:#6c757d; text-align:center;">Connecting to an available team member...</div>',
            '  </div>',
            '  <div id="kchat-widget-emoji-picker" style="display:none; background:#fff; border-top:1px solid #e9ecef; padding:8px; font-size:20px;">',
            '    <span style="display:inline-block; padding:4px 6px; cursor:pointer;" data-emoji="😊">😊</span>',
            '    <span style="display:inline-block; padding:4px 6px; cursor:pointer;" data-emoji="👍">👍</span>',
            '    <span style="display:inline-block; padding:4px 6px; cursor:pointer;" data-emoji="🎉">🎉</span>',
            '    <span style="display:inline-block; padding:4px 6px; cursor:pointer;" data-emoji="❤️">❤️</span>',
            '    <span style="display:inline-block; padding:4px 6px; cursor:pointer;" data-emoji="🙂">🙂</span>',
            '    <span style="display:inline-block; padding:4px 6px; cursor:pointer;" data-emoji="😎">😎</span>',
            '  </div>',
            '  <div style="padding: 12px; border-top:1px solid #e9ecef; background:#fff;">',
            '    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">',
            '      <button id="kchat-widget-emoji-btn" type="button" title="Insert emoji" style="border:1px solid #d9dee5; border-radius:999px; background:#fff; padding:8px 10px; cursor:pointer;">🙂</button>',
            '      <button id="kchat-widget-whiteboard-btn" type="button" title="Open whiteboard" style="border:1px solid #d9dee5; border-radius:999px; background:#fff; padding:8px 10px; cursor:pointer;"><i class="fa fa-pencil"></i></button>',
            '    </div>',
            '    <div style="display:flex; gap:10px;">',
            '      <input id="kchat-widget-input" type="text" placeholder="Type your message..." style="flex:1; border:1px solid #d9dee5; border-radius: 999px; padding: 10px 14px; outline:none; font-size:14px;" />',
            '      <button id="kchat-widget-send" type="button" style="border:none; border-radius:999px; background:' + (widgetConfig.color || '#007bff') + '; color:#fff; padding: 10px 16px; cursor:pointer; font-weight:600;">Send</button>',
            '    </div>',
            '  </div>',
            '</div>'
        ].join('');

        if (widgetConfig.position === 'left') {
            container.style.left = '20px';
            container.style.right = 'auto';
        } else {
            container.style.right = '20px';
            container.style.left = 'auto';
        }

        container.appendChild(panel);
        container.appendChild(launcher);
        document.body.appendChild(container);

        launcher.addEventListener('click', function () {
            var isHidden = panel.style.display === 'none';
            panel.style.display = isHidden ? 'block' : 'none';
            if (isHidden) {
                if (!sessionId) {
                    startChat();
                }
            }
        });

        document.getElementById('kchat-widget-close').addEventListener('click', function () {
            panel.style.display = 'none';
        });

        document.getElementById('kchat-widget-send').addEventListener('click', function () {
            sendVisitorMessage();
        });

        document.getElementById('kchat-widget-input').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                sendVisitorMessage();
            }
        });

        var fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.style.display = 'none';
        fileInput.addEventListener('change', function () {
            var file = fileInput.files && fileInput.files[0];
            if (!file || !sessionId) return;

            var formData = new FormData();
            formData.append('token', token);
            formData.append('visitor_uid', visitorUid);
            formData.append('session_id', sessionId);
            formData.append('file', file);

            fetch(apiBasePath + '/widget/send-file', {
                method: 'POST',
                body: formData
            }).then(function () {
                return requestJSON(apiBasePath + '/widget/poll', {
                    token: token,
                    visitor_uid: visitorUid,
                    session_id: sessionId,
                    after_id: lastMessageId
                });
            }).then(function (result) {
                if (result && result.messages) {
                    renderMessages(result.messages, false);
                    lastMessageId = parseInt((result.messages[result.messages.length - 1].id || 0), 10) || lastMessageId;
                }
            });
        });
        panel.appendChild(fileInput);

        var fileButton = document.createElement('button');
        fileButton.type = 'button';
        fileButton.innerHTML = '<i class="fa fa-paperclip"></i>';
        fileButton.title = 'Attach file';
        fileButton.style.border = '1px solid #d9dee5';
        fileButton.style.borderRadius = '999px';
        fileButton.style.background = '#fff';
        fileButton.style.padding = '8px 10px';
        fileButton.style.cursor = 'pointer';
        fileButton.addEventListener('click', function () {
            fileInput.click();
        });
        var actionRow = panel.querySelector('#kchat-widget-body > div:last-child');
        if (actionRow) {
            var wrapper = actionRow.querySelector('div:first-child');
            if (wrapper) {
                wrapper.appendChild(fileButton);
            }
        }

        document.getElementById('kchat-widget-emoji-btn').addEventListener('click', function () {
            var picker = document.getElementById('kchat-widget-emoji-picker');
            if (picker) {
                picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
            }
        });

        document.querySelectorAll('[data-emoji]').forEach(function (item) {
            item.addEventListener('click', function () {
                var input = document.getElementById('kchat-widget-input');
                if (!input) return;
                input.value = (input.value || '') + this.getAttribute('data-emoji');
                input.focus();
                var picker = document.getElementById('kchat-widget-emoji-picker');
                if (picker) picker.style.display = 'none';
            });
        });

        document.getElementById('kchat-widget-whiteboard-btn').addEventListener('click', function () {
            openWhiteboard();
        });
    }

    var whiteboardState = {
        shape: 'Pencil',
        points: [],
        drawing: false,
        color: '#000000',
        border: 2,
        fill: false,
        x: 0,
        y: 0
    };

    function openWhiteboard() {
        var existing = document.getElementById('kchat-widget-whiteboard');
        if (!existing) {
            var modal = document.createElement('div');
            modal.id = 'kchat-widget-whiteboard';
            modal.style.position = 'fixed';
            modal.style.inset = '0';
            modal.style.background = 'rgba(0,0,0,0.45)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '2147483648';
            modal.innerHTML = [
                '<div style="background:#fff; width: min(92vw, 700px); border-radius:16px; padding:16px; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">',
                '  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">',
                '    <strong>Whiteboard</strong>',
                '    <button type="button" data-close-whiteboard="1" style="border:none; background:#f1f3f5; border-radius:999px; width:32px; height:32px; cursor:pointer;">×</button>',
                '  </div>',
                '  <canvas id="kchat-widget-canvas" width="620" height="320" style="width:100%; max-width:100%; border:1px solid #dfe4ea; border-radius:12px; background:#fff;"></canvas>',
                '  <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:12px;">',
                '    <button type="button" data-shape="Pencil" style="padding:7px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer;">Pencil</button>',
                '    <button type="button" data-shape="Line" style="padding:7px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer;">Line</button>',
                '    <button type="button" data-shape="Rectangle" style="padding:7px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer;">Rectangle</button>',
                '    <button type="button" data-shape="Circle" style="padding:7px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer;">Circle</button>',
                '    <input type="color" id="kchat-widget-color" value="#000000" style="width:42px; height:38px; border:none; background:none; padding:0;" />',
                '    <button type="button" data-clear="1" style="padding:7px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer;">Clear</button>',
                '  </div>',
                '  <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:14px;">',
                '    <button type="button" data-close-whiteboard="1" style="border:none; background:#f1f3f5; color:#333; border-radius:8px; padding:9px 14px; cursor:pointer;">Close</button>',
                '    <button type="button" data-send-whiteboard="1" style="border:none; background:' + (widgetConfig.color || '#007bff') + '; color:#fff; border-radius:8px; padding:9px 14px; cursor:pointer;">Send</button>',
                '  </div>',
                '</div>'
            ].join('');
            document.body.appendChild(modal);

            var canvas = document.getElementById('kchat-widget-canvas');
            var ctx = canvas.getContext('2d');
            var draw = function (evt) {
                var rect = canvas.getBoundingClientRect();
                var x = evt.clientX - rect.left;
                var y = evt.clientY - rect.top;
                if (!whiteboardState.drawing) return;
                whiteboardState.points.push([whiteboardState.shape, whiteboardState.color, whiteboardState.border, [whiteboardState.x, whiteboardState.y, x, y]]);
                whiteboardState.x = x;
                whiteboardState.y = y;
                renderWhiteboardCanvas(ctx);
            };

            canvas.addEventListener('pointerdown', function (evt) {
                var rect = canvas.getBoundingClientRect();
                whiteboardState.x = evt.clientX - rect.left;
                whiteboardState.y = evt.clientY - rect.top;
                whiteboardState.drawing = true;
                whiteboardState.points.push([whiteboardState.shape, whiteboardState.color, whiteboardState.border, [whiteboardState.x, whiteboardState.y, whiteboardState.x, whiteboardState.y]]);
            });
            canvas.addEventListener('pointermove', draw);
            canvas.addEventListener('pointerup', function () {
                whiteboardState.drawing = false;
            });
            canvas.addEventListener('pointerleave', function () {
                whiteboardState.drawing = false;
            });

            modal.querySelectorAll('[data-shape]').forEach(function (button) {
                button.addEventListener('click', function () {
                    whiteboardState.shape = this.getAttribute('data-shape');
                });
            });
            modal.querySelector('[data-clear="1"]').addEventListener('click', function () {
                whiteboardState.points = [];
                renderWhiteboardCanvas(ctx);
            });
            document.getElementById('kchat-widget-color').addEventListener('input', function () {
                whiteboardState.color = this.value;
            });
            modal.querySelector('[data-close-whiteboard="1"]').addEventListener('click', function () {
                modal.remove();
            });
            modal.querySelector('[data-send-whiteboard="1"]').addEventListener('click', function () {
                var payload = JSON.stringify(whiteboardState.points);
                var input = document.getElementById('kchat-widget-input');
                if (input) {
                    input.value = payload;
                }
                modal.remove();
                sendVisitorMessage();
            });
            renderWhiteboardCanvas(ctx); 
        } else {
            existing.style.display = 'flex';
        }
    }

    function renderWhiteboardCanvas(ctx) {
        ctx.clearRect(0, 0, 620, 320);
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        whiteboardState.points.forEach(function (point) {
            if (!point || point.length < 4) return;
            var shape = point[0];
            var color = point[1] || '#000000';
            var border = point[2] || 2;
            var coords = point[3] || [];
            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.lineWidth = border;
            if (shape === 'Line') {
                ctx.moveTo(coords[0], coords[1]);
                ctx.lineTo(coords[2], coords[3]);
                ctx.stroke();
            } else if (shape === 'Rectangle') {
                ctx.strokeRect(coords[0], coords[1], coords[2] - coords[0], coords[3] - coords[1]);
            } else if (shape === 'Circle') {
                var radius = Math.sqrt(Math.pow(coords[2] - coords[0], 2) + Math.pow(coords[3] - coords[1], 2));
                ctx.arc(coords[0], coords[1], radius, 0, Math.PI * 2);
                ctx.stroke();
            } else {
                ctx.moveTo(coords[0], coords[1]);
                ctx.lineTo(coords[2], coords[3]);
                ctx.stroke();
            }
        });
    }

    function persistSession() {
        try {
            if (sessionId) {
                localStorage.setItem(storageKey + ':session_id', String(sessionId));
            }
        } catch (e) {
            // Ignore storage issues in restricted browser contexts.
        }
    }

    function renderMessages(messages, replace) {
        var container = document.getElementById('kchat-widget-messages');
        if (!container) return;

        if (replace) {
            container.innerHTML = '';
            container.dataset.hasMessages = '';
        }

        if (!messages || messages.length === 0) {
            if (!container.dataset.hasMessages) {
                container.innerHTML = '<div style="padding:16px; color:#6c757d; text-align:center;">Say hello to start the conversation.</div>';
            }
            return;
        }

        container.dataset.hasMessages = '1';

        messages.forEach(function (msg) {
            var align = msg.sender === 'visitor' ? 'left' : 'right';
            var bg = msg.sender === 'visitor' ? '#ffffff' : (widgetConfig.color || '#007bff');
            var color = msg.sender === 'visitor' ? '#212529' : '#fff';
            var border = msg.sender === 'visitor' ? '1px solid #e9ecef' : 'transparent';
            var bubble = document.createElement('div');
            bubble.style.marginBottom = '12px';
            bubble.style.textAlign = align;

            if (msg.type == 1) {
                bubble.innerHTML = '<div style="display:inline-block; max-width:80%; background:' + bg + '; color:' + color + '; border:' + border + '; border-radius: 12px; padding: 10px 12px; box-shadow: 0 1px 1px rgba(0,0,0,0.04);"><i class="fa fa-pencil-square-o"></i> Whiteboard drawing</div>';
                container.appendChild(bubble);
                return;
            }

            if (msg.type == 2) {
                var files = [];
                try {
                    files = JSON.parse(msg.message || '[]');
                } catch (e) {
                    files = [];
                }
                var linkHtml = '<div style="display:inline-block; max-width:80%; background:' + bg + '; color:' + color + '; border:' + border + '; border-radius: 12px; padding: 10px 12px; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">';
                if (files.length) {
                    files.forEach(function (file) {
                        linkHtml += '<div><a href="' + (window.location.origin || '') + '/messages/downattch/' + (file.uuid || '') + '" target="_blank" style="color:' + color + '; text-decoration: underline;">' + escapeHtml(file.Name || 'Attachment') + '</a></div>';
                    });
                } else {
                    linkHtml += 'Attachment';
                }
                linkHtml += '</div>';
                bubble.innerHTML = linkHtml;
                container.appendChild(bubble);
                return;
            }

            bubble.innerHTML = '<div style="display:inline-block; max-width:80%; background:' + bg + '; color:' + color + '; border:' + border + '; border-radius: 12px; padding: 10px 12px; box-shadow: 0 1px 1px rgba(0,0,0,0.04); white-space: pre-wrap; word-break: break-word;">' + escapeHtml(msg.message) + '</div>';
            container.appendChild(bubble);
        });

        container.scrollTop = container.scrollHeight;
    }

    function pollMessages() {
        if (!sessionId) return;

        requestJSON(apiBasePath + '/widget/poll', {
            token: token,
            visitor_uid: visitorUid,
            session_id: sessionId,
            after_id: lastMessageId
        }).then(function (result) {
            if (result && result.messages) {
                if (result.messages.length) {
                    renderMessages(result.messages, false);
                    lastMessageId = parseInt((result.messages[result.messages.length - 1].id || 0), 10) || lastMessageId;
                }
            }
        }).catch(function () {
            // Ignore polling failures and keep the widget open.
        });
    }

    function sendVisitorMessage() {
        var input = document.getElementById('kchat-widget-input');
        var message = input ? input.value.trim() : '';
        if (!message || !sessionId) {
            return;
        }

        var isWhiteboard = message.charAt(0) === '[' && message.indexOf('"') !== -1;
        requestJSON(apiBasePath + '/widget/send-message', {
            token: token,
            visitor_uid: visitorUid,
            session_id: sessionId,
            message: message,
            whiteboard: isWhiteboard ? 1 : 0
        }).then(function () {
            if (input) input.value = '';
            return requestJSON(apiBasePath + '/widget/poll', {
                token: token,
                visitor_uid: visitorUid,
                session_id: sessionId,
                after_id: lastMessageId
            });
        }).then(function (result) {
            if (result && result.messages) {
                renderMessages(result.messages, false);
                lastMessageId = parseInt((result.messages[result.messages.length - 1].id || 0), 10) || lastMessageId;
            }
        }).catch(function () {
            // Ignore send failures for now.
        });
    }

    function startChat() {
        requestJSON(apiBasePath + '/widget/start-chat', {
            token: token,
            visitor_uid: visitorUid,
            visitor_name: visitorName
        }).then(function (result) {
            if (result && result.error) {
                var body = document.getElementById('kchat-widget-messages');
                if (body) {
                    body.innerHTML = '<div style="padding:16px; color:#dc3545; text-align:center;">' + escapeHtml(result.error) + '</div>';
                }
                return;
            }

            sessionId = result && result.session_id ? result.session_id : null;
            persistSession();
            if (!sessionId) {
                return;
            }

            lastMessageId = 0;
            return requestJSON(apiBasePath + '/widget/poll', {
                token: token,
                visitor_uid: visitorUid,
                session_id: sessionId,
                after_id: 0
            });
        }).then(function (result) {
            if (result && result.messages) {
                renderMessages(result.messages, true);
                if (result.messages.length) {
                    lastMessageId = parseInt((result.messages[result.messages.length - 1].id || 0), 10) || lastMessageId;
                }
            } else {
                var messages = [
                    { sender: 'agent', message: 'Hello! How can I help you today?' }
                ];
                renderMessages(messages, true);
            }
            if (!window.__kchatWidgetInterval) {
                window.__kchatWidgetInterval = setInterval(pollMessages, 3000);
            }
        }).catch(function () {
            var body = document.getElementById('kchat-widget-messages');
            if (body) {
                body.innerHTML = '<div style="padding:16px; color:#dc3545; text-align:center;">Unable to connect right now. Please try again later.</div>';
            }
        });
    }

    requestJSON(apiBasePath + '/widget/init', {
        token: token
    }).then(function (response) {
        if (!response || response.error) {
            return;
        }

        widgetConfig = response;
        createWidget();

        if (response.agents_online === true) {
            var body = document.getElementById('kchat-widget-messages');
            if (body) {
                if (sessionId) {
                    body.innerHTML = '<div style="padding:16px; color:#6c757d; text-align:center;">Loading your previous conversation...</div>';
                    startChat();
                } else {
                    body.innerHTML = '<div style="padding:16px; color:#6c757d; text-align:center;">Hello! We are ready to help. Click the chat button to start.</div>';
                }
            }
        } else {
            var body = document.getElementById('kchat-widget-messages');
            if (body) {
                body.innerHTML = '<div style="padding:16px; color:#6c757d; text-align:center;">No agents are online right now. Please try again later.</div>';
            }
            var sendBtn = document.getElementById('kchat-widget-send');
            if (sendBtn) sendBtn.disabled = true;
        }
    }).catch(function () {
        // Ignore invalid widget config silently.
    });
})();
