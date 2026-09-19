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
    var visitorName = '';
    var widgetConfig = null;

    var widgetTranslations = {
        en: { chatWithUs: 'Chat with us', namePrompt: 'Please enter your name:', namePlaceholder: 'Your name', startChat: 'Start Chat', connecting: 'Connecting to an available team member...', inputPlaceholder: 'Type your message...', send: 'Send', insertEmoji: 'Insert emoji', openWhiteboard: 'Open whiteboard', attachFiles: 'Attach files', sayHello: 'Say hello to start the conversation.', unableConnect: 'Unable to connect right now. Please try again later.', loading: 'Loading your previous conversation...', ready: 'Hello! We are ready to help. Enter your name to start.', noAgents: 'No agents are online right now. Please try again later.', whiteboard: 'Whiteboard', whiteboardView: 'Whiteboard (View)', pencil: 'Pencil', line: 'Line', rectangle: 'Rectangle', circle: 'Circle', ellipse: 'Ellipse', eraser: 'Eraser', color: 'Color', fill: 'Fill', size: 'Size', clear: 'Clear', download: 'Download', close: 'Close', whiteboardDrawing: 'Whiteboard drawing', clickToView: '(click to view)', attachment: 'Attachment', hello: 'Hello! How can I help you today?' },
        hi: { chatWithUs: 'हमसे चैट करें', namePrompt: 'कृपया अपना नाम दर्ज करें:', namePlaceholder: 'आपका नाम', startChat: 'चैट शुरू करें', connecting: 'उपलब्ध टीम सदस्य से कनेक्ट हो रहा है...', inputPlaceholder: 'अपना संदेश लिखें...', send: 'भेजें', insertEmoji: 'इमोजी डालें', openWhiteboard: 'व्हाइटबोर्ड खोलें', attachFiles: 'फ़ाइलें संलग्न करें', sayHello: 'बातचीत शुरू करने के लिए नमस्ते कहें।', unableConnect: 'अभी कनेक्ट नहीं हो सका। कृपया बाद में पुनः प्रयास करें।', loading: 'आपकी पिछली बातचीत लोड हो रही है...', ready: 'नमस्ते! चैट शुरू करने के लिए अपना नाम दर्ज करें।', noAgents: 'अभी कोई एजेंट ऑनलाइन नहीं है। कृपया बाद में पुनः प्रयास करें।', whiteboard: 'व्हाइटबोर्ड', whiteboardView: 'व्हाइटबोर्ड (दृश्य)', pencil: 'पेंसिल', line: 'रेखा', rectangle: 'आयत', circle: 'वृत्त', ellipse: 'अंडाकार', eraser: 'रबर', color: 'रंग', fill: 'भरें', size: 'आकार', clear: 'साफ़ करें', download: 'डाउनलोड', close: 'बंद करें', whiteboardDrawing: 'व्हाइटबोर्ड चित्र', clickToView: '(देखने के लिए क्लिक करें)', attachment: 'अटैचमेंट', hello: 'नमस्ते! मैं आपकी कैसे सहायता कर सकता हूँ?' },
        es: { chatWithUs: 'Chatea con nosotros', connecting: 'Conectando con un miembro del equipo...', inputPlaceholder: 'Escribe tu mensaje...', send: 'Enviar', insertEmoji: 'Insertar emoji', openWhiteboard: 'Abrir pizarra', attachFiles: 'Adjuntar archivos', sayHello: 'Saluda para iniciar la conversación.', unableConnect: 'No se puede conectar ahora. Inténtalo de nuevo más tarde.', loading: 'Cargando tu conversación anterior...', ready: '¡Hola! Estamos listos para ayudarte. Haz clic en el botón de chat para comenzar.', noAgents: 'No hay agentes en línea. Inténtalo de nuevo más tarde.', whiteboard: 'Pizarra', whiteboardView: 'Pizarra (vista)', pencil: 'Lápiz', line: 'Línea', rectangle: 'Rectángulo', circle: 'Círculo', ellipse: 'Elipse', eraser: 'Borrador', color: 'Color', fill: 'Relleno', size: 'Tamaño', clear: 'Limpiar', download: 'Descargar', close: 'Cerrar', whiteboardDrawing: 'Dibujo en pizarra', clickToView: '(haz clic para ver)', attachment: 'Archivo adjunto', hello: '¡Hola! ¿Cómo puedo ayudarte?' },
        fr: { chatWithUs: 'Discutez avec nous', connecting: 'Connexion à un membre de l’équipe...', inputPlaceholder: 'Écrivez votre message...', send: 'Envoyer', insertEmoji: 'Insérer un emoji', openWhiteboard: 'Ouvrir le tableau blanc', attachFiles: 'Joindre des fichiers', sayHello: 'Dites bonjour pour commencer la conversation.', unableConnect: 'Connexion impossible pour le moment. Réessayez plus tard.', loading: 'Chargement de votre conversation précédente...', ready: 'Bonjour ! Nous sommes prêts à vous aider. Cliquez sur le bouton de chat pour commencer.', noAgents: 'Aucun agent n’est en ligne. Réessayez plus tard.', whiteboard: 'Tableau blanc', whiteboardView: 'Tableau blanc (aperçu)', pencil: 'Crayon', line: 'Ligne', rectangle: 'Rectangle', circle: 'Cercle', ellipse: 'Ellipse', eraser: 'Gomme', color: 'Couleur', fill: 'Remplir', size: 'Taille', clear: 'Effacer', download: 'Télécharger', close: 'Fermer', whiteboardDrawing: 'Dessin sur tableau blanc', clickToView: '(cliquer pour voir)', attachment: 'Pièce jointe', hello: 'Bonjour ! Comment puis-je vous aider ?' },
        de: { chatWithUs: 'Chatten Sie mit uns', connecting: 'Verbindung zu einem Teammitglied wird hergestellt...', inputPlaceholder: 'Nachricht eingeben...', send: 'Senden', insertEmoji: 'Emoji einfügen', openWhiteboard: 'Whiteboard öffnen', attachFiles: 'Dateien anhängen', sayHello: 'Sagen Sie Hallo, um das Gespräch zu beginnen.', unableConnect: 'Verbindung momentan nicht möglich. Bitte versuchen Sie es später erneut.', loading: 'Vorherige Unterhaltung wird geladen...', ready: 'Hallo! Wir sind bereit zu helfen. Klicken Sie zum Starten auf die Chat-Schaltfläche.', noAgents: 'Derzeit sind keine Agenten online. Bitte versuchen Sie es später erneut.', whiteboard: 'Whiteboard', whiteboardView: 'Whiteboard (Ansicht)', pencil: 'Bleistift', line: 'Linie', rectangle: 'Rechteck', circle: 'Kreis', ellipse: 'Ellipse', eraser: 'Radiergummi', color: 'Farbe', fill: 'Füllen', size: 'Größe', clear: 'Löschen', download: 'Herunterladen', close: 'Schließen', whiteboardDrawing: 'Whiteboard-Zeichnung', clickToView: '(zum Anzeigen klicken)', attachment: 'Anhang', hello: 'Hallo! Wie kann ich Ihnen helfen?' },
        pt: { chatWithUs: 'Fale conosco', connecting: 'Conectando a um membro da equipe...', inputPlaceholder: 'Digite sua mensagem...', send: 'Enviar', insertEmoji: 'Inserir emoji', openWhiteboard: 'Abrir quadro branco', attachFiles: 'Anexar arquivos', sayHello: 'Diga olá para iniciar a conversa.', unableConnect: 'Não foi possível conectar agora. Tente novamente mais tarde.', loading: 'Carregando sua conversa anterior...', ready: 'Olá! Estamos prontos para ajudar. Clique no botão de chat para começar.', noAgents: 'Nenhum agente está online. Tente novamente mais tarde.', whiteboard: 'Quadro branco', whiteboardView: 'Quadro branco (visualização)', pencil: 'Lápis', line: 'Linha', rectangle: 'Retângulo', circle: 'Círculo', ellipse: 'Elipse', eraser: 'Borracha', color: 'Cor', fill: 'Preencher', size: 'Tamanho', clear: 'Limpar', download: 'Baixar', close: 'Fechar', whiteboardDrawing: 'Desenho no quadro branco', clickToView: '(clique para ver)', attachment: 'Anexo', hello: 'Olá! Como posso ajudar?' },
        ar: { chatWithUs: 'تحدث معنا', connecting: 'جارٍ الاتصال بأحد أعضاء الفريق...', inputPlaceholder: 'اكتب رسالتك...', send: 'إرسال', insertEmoji: 'إدراج رمز تعبيري', openWhiteboard: 'فتح السبورة', attachFiles: 'إرفاق ملفات', sayHello: 'ألقِ التحية لبدء المحادثة.', unableConnect: 'يتعذر الاتصال الآن. يرجى المحاولة لاحقًا.', loading: 'جارٍ تحميل محادثتك السابقة...', ready: 'مرحبًا! نحن مستعدون للمساعدة. انقر على زر الدردشة للبدء.', noAgents: 'لا يوجد وكلاء متصلون الآن. يرجى المحاولة لاحقًا.', whiteboard: 'السبورة', whiteboardView: 'السبورة (عرض)', pencil: 'قلم', line: 'خط', rectangle: 'مستطيل', circle: 'دائرة', ellipse: 'بيضاوي', eraser: 'ممحاة', color: 'اللون', fill: 'تعبئة', size: 'الحجم', clear: 'مسح', download: 'تنزيل', close: 'إغلاق', whiteboardDrawing: 'رسم على السبورة', clickToView: '(انقر للعرض)', attachment: 'مرفق', hello: 'مرحبًا! كيف يمكنني مساعدتك؟' },
        zh: { chatWithUs: '联系我们', connecting: '正在连接可用的团队成员...', inputPlaceholder: '输入您的消息...', send: '发送', insertEmoji: '插入表情', openWhiteboard: '打开白板', attachFiles: '添加文件', sayHello: '打个招呼开始对话。', unableConnect: '暂时无法连接，请稍后重试。', loading: '正在加载您之前的对话...', ready: '您好！我们已准备好为您提供帮助。点击聊天按钮开始。', noAgents: '目前没有客服在线，请稍后重试。', whiteboard: '白板', whiteboardView: '白板（查看）', pencil: '铅笔', line: '直线', rectangle: '矩形', circle: '圆形', ellipse: '椭圆', eraser: '橡皮擦', color: '颜色', fill: '填充', size: '大小', clear: '清除', download: '下载', close: '关闭', whiteboardDrawing: '白板绘图', clickToView: '（点击查看）', attachment: '附件', hello: '您好！有什么可以帮您？' },
        ja: { chatWithUs: 'チャットでお問い合わせ', connecting: '対応可能な担当者に接続しています...', inputPlaceholder: 'メッセージを入力...', send: '送信', insertEmoji: '絵文字を挿入', openWhiteboard: 'ホワイトボードを開く', attachFiles: 'ファイルを添付', sayHello: '挨拶して会話を始めましょう。', unableConnect: '現在接続できません。後でもう一度お試しください。', loading: '以前の会話を読み込んでいます...', ready: 'こんにちは！サポートの準備ができています。チャットボタンをクリックして開始してください。', noAgents: '現在オンラインの担当者はいません。後でもう一度お試しください。', whiteboard: 'ホワイトボード', whiteboardView: 'ホワイトボード（表示）', pencil: '鉛筆', line: '線', rectangle: '長方形', circle: '円', ellipse: '楕円', eraser: '消しゴム', color: '色', fill: '塗りつぶし', size: 'サイズ', clear: 'クリア', download: 'ダウンロード', close: '閉じる', whiteboardDrawing: 'ホワイトボードの描画', clickToView: '（クリックして表示）', attachment: '添付ファイル', hello: 'こんにちは！どのようにお手伝いできますか？' }
    };

    function widgetText(key) {
        var language = widgetConfig && widgetConfig.language ? widgetConfig.language : 'en';
        var translations = widgetTranslations[language] || widgetTranslations.en;
        return translations[key] || widgetTranslations.en[key] || key;
    }

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

        var savedVisitorName = localStorage.getItem(storageKey + ':visitor_name');
        if (savedVisitorName) {
            visitorName = savedVisitorName;
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

    function decodeHtmlEntities(str) {
        var doc = document.createElement('div');
        doc.innerHTML = str;
        return doc.textContent || doc.innerText || '';
    }

    // ============================================================
    // Emoji Data — organized by category to match emojionearea richness
    // ============================================================
    var emojiCategories = [
        {
            name: 'Smileys',
            icon: '😀',
            emojis: ['😀','😁','😂','🤣','😃','😄','😅','😆','😉','😊','😋','😎','😍','🥰','😘','😗','😙','😚','🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪','😫','😴','😌','😛','😜','😝','🤤','😒','😓','😔','😕','🙃','🤑','😲','🤯','😳','🥺','😱','😨','😰','😢','😭','😤','😠','😡','🤬','😈','👿','💀','☠️','💩','🤡','👹','👺','👻','👽','👾','🤖']
        },
        {
            name: 'Gestures',
            icon: '👍',
            emojis: ['👋','🤚','🖐️','✋','🖖','👌','🤌','🤏','✌️','🤞','🤟','🤘','🤙','👈','👉','👆','🖕','👇','☝️','👍','👎','✊','👊','🤛','🤜','👏','🙌','👐','🤲','🤝','🙏','✍️','💪','🦾','🦿','🦵','🦶']
        },
        {
            name: 'Hearts',
            icon: '❤️',
            emojis: ['❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣️','💕','💞','💓','💗','💖','💘','💝','💟','♥️','🫶']
        },
        {
            name: 'Objects',
            icon: '🎉',
            emojis: ['🎉','🎊','🎈','🎁','🎀','🏆','🏅','🥇','🥈','🥉','⚽','🏀','🏈','⚾','🎾','🏐','🎯','🔔','🎵','🎶','🎤','🎧','🎸','🎹','🎺','🎻','📱','💻','⌨️','🖥️','📷','📹','📞','📚','📖','✏️','📝','📎','📌','📍','✂️','🔑','🔒','🔓']
        },
        {
            name: 'Food',
            icon: '🍕',
            emojis: ['🍕','🍔','🍟','🌭','🍿','🧂','🥚','🍳','🧈','🥞','🧇','🥓','🥩','🍗','🍖','🌮','🌯','🥙','🍝','🍜','🍲','🍛','🍣','🍱','🍙','🍚','🍘','🍥','🥟','🍢','🍡','🍧','🍨','🍦','🥧','🧁','🍰','🎂','🍮','🍭','🍬','🍫','🍩','🍪','☕','🍵','🧃','🥤','🍺','🍻']
        },
        {
            name: 'Nature',
            icon: '🌸',
            emojis: ['🌸','🌺','🌻','🌹','🌷','🌼','💐','🌿','☘️','🍀','🍃','🍂','🍁','🌾','🌵','🎄','🌲','🌳','🌴','🐶','🐱','🐭','🐹','🐰','🦊','🐻','🐼','🐨','🐯','🦁','🐮','🐷','🐸','🐵','🐔','🐧','🐦','🦅','🦆','🦉','🐺','🐗','🐴','🦄','🐝','🐛','🦋','🐌','🐞']
        },
        {
            name: 'Travel',
            icon: '✈️',
            emojis: ['🚗','🚕','🚙','🚌','🚎','🏎️','🚓','🚑','🚒','🚐','🚚','🚛','🚜','🛵','🏍️','🚲','🛴','🚃','🚄','🚅','🚆','🚇','🚈','✈️','🛩️','🚀','🛸','🚁','⛵','🚤','🛥️','🗼','🗽','⛩️','🕌','🕍','⛪','🏛️','🏰','🏯','🌈','🌊','🌙','⭐','🌟','💫','☀️','🌤️','⛅','🌥️']
        },
        {
            name: 'Symbols',
            icon: '✅',
            emojis: ['✅','❌','⭕','❗','❓','‼️','⁉️','💯','🔥','💥','💫','💦','💨','🕳️','💣','💬','👁️‍🗨️','🗯️','💭','🔊','🔇','🔈','🔉','📢','📣','🏁','🚩','🏴','🏳️','🔴','🟠','🟡','🟢','🔵','🟣','🟤','⚫','⚪','✔️','☑️','🔘']
        }
    ];

    // ============================================================
    // Build the widget UI
    // ============================================================
    function createWidget() {
        // Inject Font Awesome if not present
        if (!document.querySelector('link[href*="font-awesome"]')) {
            var fa = document.createElement('link');
            fa.rel = 'stylesheet';
            fa.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';
            document.head.appendChild(fa);
        }

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
        launcher.title = widgetConfig.title || widgetText('chatWithUs');
        launcher.innerHTML = '<i class="fa ' + (widgetConfig.icon || 'fa-comments') + '"></i>';

        var panel = document.createElement('div');
        panel.id = 'kchat-widget-panel';
        panel.style.display = 'none';
        panel.style.width = '380px';
        panel.style.maxWidth = '92vw';
        panel.style.background = '#fff';
        panel.style.border = '1px solid rgba(0,0,0,0.08)';
        panel.style.borderRadius = '16px';
        panel.style.boxShadow = '0 18px 40px rgba(0,0,0,0.18)';
        panel.style.overflow = 'hidden';
        panel.style.marginBottom = '18px';
        panel.style.position = 'relative';

        var themeColor = widgetConfig.color || '#007bff';

        panel.innerHTML = [
            '<div style="padding: 14px 16px; background: ' + themeColor + '; color: #fff; display: flex; align-items: center; justify-content: space-between;">',
            '  <div style="display:flex; align-items:center; gap:10px; font-weight:700;">',
            '    <span style="display:inline-flex; width:30px; height:30px; border-radius:50%; background: rgba(255,255,255,0.18); align-items:center; justify-content:center;"><i class="fa ' + (widgetConfig.icon || 'fa-comments') + '"></i></span>',
            '    <span>' + escapeHtml(widgetConfig.title || widgetText('chatWithUs')) + '</span>',
            '  </div>',
            '  <button type="button" id="kchat-widget-close" style="background:transparent; border:none; color:#fff; font-size:24px; cursor:pointer;">×</button>',
            '</div>',
            '<div id="kchat-widget-body" style="height: 370px; display:flex; flex-direction:column; background:#f8f9fb;">',
            '  <div id="kchat-widget-messages" style="flex:1; overflow:auto; padding:12px; font-size:14px; line-height:1.5; color:#212529;">',
            '    <div style="padding:16px; color:#6c757d; text-align:center;">' + widgetText('connecting') + '</div>',
            '  </div>',
            '  <div id="kchat-widget-name-bar" style="display:flex; gap:8px; padding:10px 12px; border-top:1px solid #e9ecef; background:#fff;">',
            '    <input id="kchat-widget-name" type="text" maxlength="120" placeholder="' + widgetText('namePlaceholder') + '" style="flex:1; border:1px solid #d9dee5; border-radius:999px; padding:8px 12px; outline:none; font-size:14px;" />',
            '    <button id="kchat-widget-start" type="button" style="border:none; border-radius:999px; background:' + themeColor + '; color:#fff; padding:8px 12px; cursor:pointer; font-weight:600;">' + widgetText('startChat') + '</button>',
            '  </div>',
            '  <div id="kchat-widget-emoji-picker" style="display:none; background:#fff; border-top:1px solid #e9ecef; max-height:220px; overflow:hidden; flex-direction:column;">',
            '    <div id="kchat-widget-emoji-tabs" style="display:flex; border-bottom:1px solid #eee; padding:4px 8px; gap:2px; flex-shrink:0; overflow-x:auto;"></div>',
            '    <div id="kchat-widget-emoji-grid" style="flex:1; overflow-y:auto; padding:8px; font-size:22px; line-height:1.6;"></div>',
            '  </div>',
            '  <div style="padding: 10px 12px; border-top:1px solid #e9ecef; background:#fff;">',
            '    <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">',
            '      <button id="kchat-widget-emoji-btn" type="button" title="' + widgetText('insertEmoji') + '" style="border:1px solid #d9dee5; border-radius:999px; background:#fff; padding:6px 9px; cursor:pointer; font-size:16px;">🙂</button>',
            '      <button id="kchat-widget-whiteboard-btn" type="button" title="' + widgetText('openWhiteboard') + '" style="border:1px solid #d9dee5; border-radius:999px; background:#fff; padding:6px 9px; cursor:pointer; font-size:14px;"><i class="fa fa-pencil"></i></button>',
            '      <button id="kchat-widget-file-btn" type="button" title="' + widgetText('attachFiles') + '" style="border:1px solid #d9dee5; border-radius:999px; background:#fff; padding:6px 9px; cursor:pointer; font-size:14px;"><i class="fa fa-paperclip"></i></button>',
            '      <input id="kchat-widget-file-input" type="file" multiple="multiple" style="display:none;" />',
            '    </div>',
            '    <div style="display:flex; gap:8px;">',
            '      <input id="kchat-widget-input" type="text" placeholder="' + widgetText('inputPlaceholder') + '" style="flex:1; border:1px solid #d9dee5; border-radius: 999px; padding: 10px 14px; outline:none; font-size:14px;" />',
            '      <button id="kchat-widget-send" type="button" style="border:none; border-radius:999px; background:' + themeColor + '; color:#fff; padding: 10px 16px; cursor:pointer; font-weight:600;">' + widgetText('send') + '</button>',
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

        // ---- Build emoji picker content ----
        buildEmojiPicker();

        // ---- Event Listeners ----

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

        var nameInput = document.getElementById('kchat-widget-name');
        if (nameInput) {
            nameInput.value = visitorName;
            nameInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    startChat();
                }
            });
        }

        document.getElementById('kchat-widget-start').addEventListener('click', function () {
            startChat();
        });

        document.getElementById('kchat-widget-input').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                sendVisitorMessage();
            }
        });

        // Emoji button toggle
        document.getElementById('kchat-widget-emoji-btn').addEventListener('click', function () {
            var picker = document.getElementById('kchat-widget-emoji-picker');
            if (picker) {
                var isVisible = picker.style.display === 'flex';
                picker.style.display = isVisible ? 'none' : 'flex';
            }
        });

        // Whiteboard button
        document.getElementById('kchat-widget-whiteboard-btn').addEventListener('click', function () {
            openWhiteboard(false);
        });

        // File attachment
        document.getElementById('kchat-widget-file-btn').addEventListener('click', function () {
            document.getElementById('kchat-widget-file-input').click();
        });

        document.getElementById('kchat-widget-file-input').addEventListener('change', function () {
            var files = this.files;
            if (!files || files.length === 0 || !sessionId) return;

            for (var i = 0; i < files.length; i++) {
                (function(file) {
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
                })(files[i]);
            }
            // Clear the input so same file can be re-selected
            this.value = '';
        });
    }

    // ============================================================
    // Emoji Picker Builder
    // ============================================================
    function buildEmojiPicker() {
        var tabsContainer = document.getElementById('kchat-widget-emoji-tabs');
        var gridContainer = document.getElementById('kchat-widget-emoji-grid');
        if (!tabsContainer || !gridContainer) return;

        var activeCategory = 0;

        function renderCategory(index) {
            activeCategory = index;
            var cat = emojiCategories[index];
            gridContainer.innerHTML = '';

            cat.emojis.forEach(function (emoji) {
                var span = document.createElement('span');
                span.style.display = 'inline-block';
                span.style.padding = '3px 5px';
                span.style.cursor = 'pointer';
                span.style.borderRadius = '6px';
                span.style.transition = 'background 0.15s';
                span.textContent = emoji;
                span.addEventListener('mouseenter', function () { this.style.background = '#f0f0f0'; });
                span.addEventListener('mouseleave', function () { this.style.background = 'transparent'; });
                span.addEventListener('click', function () {
                    var input = document.getElementById('kchat-widget-input');
                    if (!input) return;
                    input.value = (input.value || '') + emoji;
                    input.focus();
                });
                gridContainer.appendChild(span);
            });

            // Update tab active state
            var tabs = tabsContainer.querySelectorAll('[data-cat-idx]');
            tabs.forEach(function (tab) {
                tab.style.background = parseInt(tab.getAttribute('data-cat-idx')) === index ? '#e8f0fe' : 'transparent';
                tab.style.borderRadius = '8px';
            });
        }

        emojiCategories.forEach(function (cat, idx) {
            var tab = document.createElement('button');
            tab.type = 'button';
            tab.setAttribute('data-cat-idx', idx);
            tab.style.border = 'none';
            tab.style.background = idx === 0 ? '#e8f0fe' : 'transparent';
            tab.style.borderRadius = '8px';
            tab.style.padding = '4px 8px';
            tab.style.cursor = 'pointer';
            tab.style.fontSize = '18px';
            tab.title = cat.name;
            tab.textContent = cat.icon;
            tab.addEventListener('click', function () {
                renderCategory(idx);
            });
            tabsContainer.appendChild(tab);
        });

        renderCategory(0);
    }

    // ============================================================
    // Whiteboard — full parity with main chat
    // ============================================================
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

    function openWhiteboard(readOnly, existingPoints) {
        var existing = document.getElementById('kchat-widget-whiteboard');
        if (existing) existing.remove();

        var themeColor = widgetConfig.color || '#007bff';

        var modal = document.createElement('div');
        modal.id = 'kchat-widget-whiteboard';
        modal.style.position = 'fixed';
        modal.style.inset = '0';
        modal.style.background = 'rgba(0,0,0,0.45)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = '2147483648';

        var toolsHtml = '';
        var footerHtml = '';

        if (!readOnly) {
            toolsHtml = [
                '<div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:12px; align-items:center;">',
                '  <button type="button" data-shape="Pencil" class="wb-tool wb-active" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#e8f0fe; cursor:pointer; font-size:13px;">✏️ ' + widgetText('pencil') + '</button>',
                '  <button type="button" data-shape="Line" class="wb-tool" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">📏 ' + widgetText('line') + '</button>',
                '  <button type="button" data-shape="Rectangle" class="wb-tool" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">▭ ' + widgetText('rectangle') + '</button>',
                '  <button type="button" data-shape="Circle" class="wb-tool" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">⭕ ' + widgetText('circle') + '</button>',
                '  <button type="button" data-shape="ellipse" class="wb-tool" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">⬮ ' + widgetText('ellipse') + '</button>',
                '  <button type="button" data-shape="clearRect" class="wb-tool" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">🧹 ' + widgetText('eraser') + '</button>',
                '  <input type="color" id="kchat-wb-color" value="#000000" title="' + widgetText('color') + '" style="width:36px; height:32px; border:1px solid #dfe4ea; border-radius:8px; background:none; padding:2px; cursor:pointer;" />',
                '  <label style="display:flex; align-items:center; gap:4px; font-size:13px; cursor:pointer;"><input type="checkbox" id="kchat-wb-fill" /> ' + widgetText('fill') + '</label>',
                '  <label style="display:flex; align-items:center; gap:4px; font-size:13px;">' + widgetText('size') + ': <input type="number" id="kchat-wb-border" value="2" min="1" max="20" style="width:48px; padding:4px; border:1px solid #dfe4ea; border-radius:6px; font-size:13px;" /></label>',
                '  <button type="button" data-clear="1" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">🗑️ ' + widgetText('clear') + '</button>',
                '  <button type="button" data-download="1" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">💾 ' + widgetText('download') + '</button>',
                '</div>'
            ].join('');

            footerHtml = [
                '<div style="display:flex; justify-content:flex-end; gap:10px; margin-top:14px;">',
                '  <button type="button" data-close-whiteboard="1" style="border:none; background:#f1f3f5; color:#333; border-radius:8px; padding:9px 14px; cursor:pointer;">' + widgetText('close') + '</button>',
                '  <button type="button" data-send-whiteboard="1" style="border:none; background:' + themeColor + '; color:#fff; border-radius:8px; padding:9px 14px; cursor:pointer;">' + widgetText('send') + '</button>',
                '</div>'
            ].join('');
        } else {
            footerHtml = [
                '<div style="display:flex; justify-content:flex-end; gap:10px; margin-top:14px;">',
                '  <button type="button" data-download="1" style="padding:6px 10px; border-radius:8px; border:1px solid #dfe4ea; background:#fff; cursor:pointer; font-size:13px;">💾 ' + widgetText('download') + '</button>',
                '  <button type="button" data-close-whiteboard="1" style="border:none; background:#f1f3f5; color:#333; border-radius:8px; padding:9px 14px; cursor:pointer;">' + widgetText('close') + '</button>',
                '</div>'
            ].join('');
        }

        modal.innerHTML = [
            '<div style="background:#fff; width: min(92vw, 700px); border-radius:16px; padding:16px; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">',
            '  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">',
            '    <strong>' + (readOnly ? widgetText('whiteboardView') : widgetText('whiteboard')) + '</strong>',
            '    <button type="button" data-close-whiteboard="1" style="border:none; background:#f1f3f5; border-radius:999px; width:32px; height:32px; cursor:pointer;">×</button>',
            '  </div>',
            '  <canvas id="kchat-wb-canvas" width="620" height="400" style="width:100%; max-width:100%; border:1px solid #dfe4ea; border-radius:12px; background:#fff;"></canvas>',
            toolsHtml,
            footerHtml,
            '</div>'
        ].join('');
        document.body.appendChild(modal);

        var canvas = document.getElementById('kchat-wb-canvas');
        var ctx = canvas.getContext('2d');

        if (readOnly && existingPoints) {
            // Render existing drawing read-only
            existingPoints.forEach(function(p) { drawPoint(ctx, p); });
        }

        if (!readOnly) {
            // Reset whiteboard state for new drawing
            whiteboardState.points = [];
            whiteboardState.shape = 'Pencil';
            whiteboardState.color = '#000000';
            whiteboardState.border = 2;
            whiteboardState.fill = false;
            whiteboardState.drawing = false;

            var go = false;

            function getPos(evt) {
                var rect = canvas.getBoundingClientRect();
                var scaleX = canvas.width / rect.width;
                var scaleY = canvas.height / rect.height;
                return {
                    x: (evt.clientX - rect.left) * scaleX,
                    y: (evt.clientY - rect.top) * scaleY
                };
            }

            canvas.addEventListener('pointerdown', function (evt) {
                var pos = getPos(evt);
                whiteboardState.x = pos.x;
                whiteboardState.y = pos.y;
                go = true;

                if (whiteboardState.shape === 'Pencil') {
                    whiteboardState.points.push([whiteboardState.shape, whiteboardState.color, whiteboardState.border, [[pos.x, pos.y]]]);
                } else {
                    whiteboardState.points.push([whiteboardState.shape, whiteboardState.color, whiteboardState.border, [[pos.x, pos.y, pos.x, pos.y]], whiteboardState.fill]);
                }
            });

            canvas.addEventListener('pointermove', function (evt) {
                if (!go) return;
                var pos = getPos(evt);
                whiteboardState.x = pos.x;
                whiteboardState.y = pos.y;

                var last = whiteboardState.points[whiteboardState.points.length - 1];
                if (whiteboardState.shape === 'Pencil') {
                    last[3].push([pos.x, pos.y]);
                } else {
                    last[1] = whiteboardState.color;
                    last[2] = whiteboardState.border;
                    last[3][0][2] = pos.x;
                    last[3][0][3] = pos.y;
                    last[4] = whiteboardState.fill;
                }

                // Redraw
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                whiteboardState.points.forEach(function(p) { drawPoint(ctx, p); });
            });

            canvas.addEventListener('pointerup', function () {
                go = false;
            });
            canvas.addEventListener('pointerleave', function () {
                go = false;
            });

            // Tool buttons
            modal.querySelectorAll('[data-shape]').forEach(function (button) {
                button.addEventListener('click', function () {
                    whiteboardState.shape = this.getAttribute('data-shape');
                    // Highlight active tool
                    modal.querySelectorAll('.wb-tool').forEach(function (b) {
                        b.style.background = '#fff';
                    });
                    this.style.background = '#e8f0fe';
                });
            });

            // Clear
            modal.querySelector('[data-clear="1"]').addEventListener('click', function () {
                whiteboardState.points = [];
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });

            // Color
            document.getElementById('kchat-wb-color').addEventListener('input', function () {
                whiteboardState.color = this.value;
            });

            // Fill
            document.getElementById('kchat-wb-fill').addEventListener('change', function () {
                whiteboardState.fill = this.checked;
            });

            // Border size
            document.getElementById('kchat-wb-border').addEventListener('change', function () {
                whiteboardState.border = parseInt(this.value) || 2;
            });

            // Send
            modal.querySelector('[data-send-whiteboard="1"]').addEventListener('click', function () {
                var payload = JSON.stringify(whiteboardState.points);
                var input = document.getElementById('kchat-widget-input');
                if (input) {
                    input.value = payload;
                }
                modal.remove();
                sendVisitorMessage(true);
            });
        }

        // Download
        var downloadBtns = modal.querySelectorAll('[data-download="1"]');
        downloadBtns.forEach(function(btn) {
            btn.addEventListener('click', function () {
                var link = document.createElement('a');
                link.href = canvas.toDataURL();
                link.download = 'KChat-whiteboard.png';
                link.click();
            });
        });

        // Close
        modal.querySelectorAll('[data-close-whiteboard="1"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                modal.remove();
            });
        });
    }

    // Draw a single whiteboard point (matching main chat's draw() function exactly)
    function drawPoint(ctx, point) {
        if (!point || point.length < 4) return;
        var shape = point[0];
        var color = point[1] || '#000000';
        var border = point[2] || 2;
        var coords = point[3] || [];
        var fill = point[4] || false;

        ctx.beginPath();
        ctx.lineWidth = border;
        ctx.fillStyle = color;
        ctx.strokeStyle = color;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        if (shape === 'Line') {
            ctx.moveTo(coords[0][0], coords[0][1]);
            ctx.lineTo(coords[0][2], coords[0][3]);
        } else if (shape === 'Circle') {
            var radius = Math.sqrt(Math.pow(coords[0][2] - coords[0][0], 2) + Math.pow(coords[0][3] - coords[0][1], 2));
            ctx.arc(coords[0][0], coords[0][1], radius, 0, Math.PI * 2);
        } else if (shape === 'Rectangle') {
            if (fill) {
                ctx.fillRect(coords[0][0], coords[0][1], coords[0][2] - coords[0][0], coords[0][3] - coords[0][1]);
            } else {
                ctx.strokeRect(coords[0][0], coords[0][1], coords[0][2] - coords[0][0], coords[0][3] - coords[0][1]);
            }
            return; // strokeRect/fillRect don't need stroke() call
        } else if (shape === 'clearRect') {
            ctx.clearRect(coords[0][0], coords[0][1], coords[0][2] - coords[0][0], coords[0][3] - coords[0][1]);
            return;
        } else if (shape === 'ellipse') {
            if (ctx.ellipse) {
                ctx.ellipse(coords[0][0], coords[0][1], Math.abs(coords[0][2] - coords[0][0]), Math.abs(coords[0][3] - coords[0][1]), 0, 0, Math.PI * 2, false);
            }
        } else if (shape === 'Pencil') {
            if (coords.length > 0) {
                ctx.moveTo(coords[0][0], coords[0][1]);
                coords.forEach(function (c) {
                    ctx.lineTo(c[0], c[1]);
                });
            }
        }

        if (fill) ctx.fill();
        ctx.stroke();
    }

    // ============================================================
    // Message rendering
    // ============================================================
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

        var themeColor = widgetConfig.color || '#007bff';

        messages.forEach(function (msg) {
            var isVisitor = msg.sender === 'visitor';
            var align = isVisitor ? 'right' : 'left';
            var bg = isVisitor ? '#ffffff' : themeColor;
            var color = isVisitor ? '#212529' : '#fff';
            var border = isVisitor ? '1px solid #e9ecef' : 'transparent';
            var bubble = document.createElement('div');
            bubble.style.marginBottom = '12px';
            bubble.style.textAlign = align;

            // TYPE 1 — Whiteboard drawing
            if (msg.type == 1) {
                var wbBtn = document.createElement('div');
                wbBtn.style.display = 'inline-block';
                wbBtn.style.maxWidth = '80%';
                wbBtn.style.background = bg;
                wbBtn.style.color = color;
                wbBtn.style.border = border;
                wbBtn.style.borderRadius = '12px';
                wbBtn.style.padding = '10px 12px';
                wbBtn.style.boxShadow = '0 1px 1px rgba(0,0,0,0.04)';
                wbBtn.style.cursor = 'pointer';
                wbBtn.innerHTML = '<i class="fa fa-pencil-square-o"></i> ' + widgetText('whiteboardDrawing') + ' <small style="opacity:0.7;">' + widgetText('clickToView') + '</small>';
                wbBtn.addEventListener('click', function () {
                    var rawMsg = msg.raw_message || msg.message;
                    try {
                        var decoded = decodeHtmlEntities(rawMsg);
                        var pts = JSON.parse(decoded);
                        openWhiteboard(true, pts);
                    } catch (e) {
                        // Try without decoding
                        try {
                            var pts2 = JSON.parse(rawMsg);
                            openWhiteboard(true, pts2);
                        } catch (e2) {
                            // ignore
                        }
                    }
                });
                bubble.appendChild(wbBtn);
                container.appendChild(bubble);
                return;
            }

            // TYPE 2 — File attachment
            if (msg.type == 2) {
                var files = [];
                try {
                    var rawMsg2 = msg.raw_message || msg.message;
                    files = JSON.parse(decodeHtmlEntities(rawMsg2));
                } catch (e) {
                    try { files = JSON.parse(msg.raw_message || msg.message); } catch (e2) { files = []; }
                }
                var linkHtml = '<div style="display:inline-block; max-width:80%; background:' + bg + '; color:' + color + '; border:' + border + '; border-radius: 12px; padding: 10px 12px; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">';
                if (files.length) {
                    files.forEach(function (file) {
                        linkHtml += '<div style="margin:3px 0;"><i class="fa fa-file" style="margin-right:6px;"></i><a href="' + apiBase + '/messages/downattch/' + (file.uuid || '') + '" target="_blank" style="color:' + color + '; text-decoration: underline;">' + escapeHtml(file.Name || 'Attachment') + '</a></div>';
                    });
                } else {
                    linkHtml += '<i class="fa fa-paperclip"></i> ' + widgetText('attachment');
                }
                linkHtml += '</div>';
                bubble.innerHTML = linkHtml;
                container.appendChild(bubble);
                return;
            }

            // TYPE 0 — Regular text message
            var displayMsg = msg.message;
            // Decode HTML entities from server, then escape for display
            displayMsg = escapeHtml(decodeHtmlEntities(displayMsg));
            bubble.innerHTML = '<div style="display:inline-block; max-width:80%; background:' + bg + '; color:' + color + '; border:' + border + '; border-radius: 12px; padding: 10px 12px; box-shadow: 0 1px 1px rgba(0,0,0,0.04); white-space: pre-wrap; word-break: break-word;">' + displayMsg + '</div>';
            container.appendChild(bubble);
        });

        container.scrollTop = container.scrollHeight;
    }

    // ============================================================
    // Messaging
    // ============================================================
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

    function sendVisitorMessage(isWhiteboard) {
        var input = document.getElementById('kchat-widget-input');
        var message = input ? input.value.trim() : '';
        if (!message || !sessionId) {
            return;
        }

        var whiteboard = isWhiteboard ? 1 : 0;
        requestJSON(apiBasePath + '/widget/send-message', {
            token: token,
            visitor_uid: visitorUid,
            session_id: sessionId,
            message: message,
            whiteboard: whiteboard
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
        if (!visitorName) {
            var nameInput = document.getElementById('kchat-widget-name');
            visitorName = nameInput ? nameInput.value.trim().slice(0, 120) : '';
            if (!visitorName) {
                if (nameInput) {
                    nameInput.focus();
                }
                return;
            }
            try {
                localStorage.setItem(storageKey + ':visitor_name', visitorName);
            } catch (e) {
                // Ignore storage issues in restricted browser contexts.
            }
        }

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
            var nameBar = document.getElementById('kchat-widget-name-bar');
            if (nameBar) {
                nameBar.style.display = 'none';
            }
            persistSession();
            if (!sessionId) {
                return;
            }

            lastMessageId = 0;
            return requestJSON(apiBasePath + '/widget/poll', {
                token: token,
                visitor_uid: visitorUid,
                visitor_name: visitorName,
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
                    { sender: 'agent', message: widgetText('hello') }
                ];
                renderMessages(messages, true);
            }
            if (!window.__kchatWidgetInterval) {
                window.__kchatWidgetInterval = setInterval(pollMessages, 3000);
            }
        }).catch(function () {
            var body = document.getElementById('kchat-widget-messages');
            if (body) {
                body.innerHTML = '<div style="padding:16px; color:#dc3545; text-align:center;">' + widgetText('unableConnect') + '</div>';
            }
        });
    }

    // ============================================================
    // Initialize
    // ============================================================
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
                    body.innerHTML = '<div style="padding:16px; color:#6c757d; text-align:center;">' + widgetText('loading') + '</div>';
                    startChat();
                } else {
                    body.innerHTML = '<div style="padding:16px; color:#6c757d; text-align:center;">' + widgetText('ready') + '</div>';
                }
            }
        } else {
            var body = document.getElementById('kchat-widget-messages');
            if (body) {
                body.innerHTML = '<div style="padding:16px; color:#6c757d; text-align:center;">' + widgetText('noAgents') + '</div>';
            }
            var sendBtn = document.getElementById('kchat-widget-send');
            if (sendBtn) sendBtn.disabled = true;
        }
    }).catch(function () {
        // Ignore invalid widget config silently.
    });
})();
