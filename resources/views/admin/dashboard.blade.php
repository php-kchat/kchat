@extends('admin.master')

@section('title', 'Dashboard')

@section('header')
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/kchat.css" rel="stylesheet">
	<link href="/css/dash.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.bundle.min.js"></script>
	<script src="/js/chart.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
@endsection

@section('body')
<div class="col-md-10 pt-3">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container">
    <div class="row">
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">All your total Message</h6>
                    <h2 class="text-right"><i class="fa fa-users f-left"></i><span>{{ $current_user_messages_count }}</span></h2>
                    <p class="m-b-0">This Month<span class="f-right">{{ $current_user_messages_count_this_month }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">All your total Conversations</h6>
                    <h2 class="text-right"><i class="fa fa-users f-left"></i><span>{{ $current_user_conversations_count }}</span></h2>
                    <p class="m-b-0">This Month<span class="f-right">{{ $current_user_new_conversations_this_month }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Total User's</h6>
                    <h2 class="text-right"><i class="fa fa-users f-left"></i><span>{{ $users_count }}</span></h2>
                    <p class="m-b-0">This Month<span class="f-right">{{ $new_users_this_month }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Total Message's</h6>
                    <h2 class="text-right"><i class="fa fa-envelope f-left"></i><span>{{ $messages_count }}</span></h2>
                    <p class="m-b-0">This Month<span class="f-right">{{ $new_messages_this_month }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Total conversation's</h6>
                    <h2 class="text-right"><i class="fa fa-comments-o f-left"></i><span>{{ $conversations_count }}</span></h2>
                    <p class="m-b-0">This Month<span class="f-right">{{ $new_conversations_this_month }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Average message's per user</h6>
                    <h2 class="text-right"><i class="fa fa-user-circle f-left"></i><span>{{ $average_messages_peruser }}</span></h2>
                    <p class="m-b-0"> <span class="f-right"> </span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-weight order-card">
                <div class="card-block">
                    <canvas id="Chart1"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-weight order-card">
                <div class="card-block">
                    <canvas id="Chart2"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-weight order-card">
                <div class="card-block">
                    <canvas id="Chart3"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-weight order-card">
                <div class="card-block">
                    <canvas id="Chart4"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-weight order-card">
                <div class="card-block">
                    <canvas id="Chart5"></canvas>
                </div>
            </div>
        </div>
	</div>
</div>
    </div>
<script>

  const ctx1 = document.getElementById('Chart1');

  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: @json($dates),
      datasets: [{
        responsive: true,
        label: 'All message\'s per day',
        data: @json($new_messages_perday),
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  
</script>
<script>

  const ctx2 = document.getElementById('Chart2');

  new Chart(ctx2, {
    type: 'line',
    data: {
      labels: @json($dates),
      datasets: [{
        responsive: true,
        label: 'All user\'s per day',
        data: @json($new_users_perday),
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  
</script>
<script>

  const ctx3 = document.getElementById('Chart3');

  new Chart(ctx3, {
    type: 'line',
    data: {
      labels: @json($dates),
      datasets: [{
        responsive: true,
        label: 'All conversation\'s per day',
        data: @json($new_conversations_perday),
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  
</script>
<script>

  const ctx4 = document.getElementById('Chart4');

  new Chart(ctx4, {
    type: 'line',
    data: {
      labels: @json($dates),
      datasets: [{
        responsive: true,
        label: 'All your message\'s per day',
        data: @json($current_user_new_messages_perday),
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  
</script>
<script>

  const ctx5 = document.getElementById('Chart5');

  new Chart(ctx5, {
    type: 'line',
    data: {
      labels: @json($dates),
      datasets: [{
        responsive: true,
        label: 'All your conversation\'s per day',
        data: @json($current_user_new_conversations_perday),
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  
</script>
@endsection
				  
@section('script')
<script type="text/javascript">

</script>
@endsection
