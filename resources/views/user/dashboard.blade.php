@extends('user.master')

@section('title', __("lang.dashboard"))

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
                    <h6 class="m-b-20">{{ __("lang.all-your-total-message") }}</h6>
                    <h2 class="text-right"><i class="fa fa-users f-left"></i><span>{{ $current_user_messages_count }}</span></h2>
                    <p class="m-b-0">{{ __("lang.this-month") }}<span class="f-right">{{ $current_user_messages_count_this_month }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">{{ __("lang.all-your-total-conversations") }}</h6>
                    <h2 class="text-right"><i class="fa fa-users f-left"></i><span>{{ $current_user_conversations_count }}</span></h2>
                    <p class="m-b-0">{{ __("lang.this-month") }}<span class="f-right">{{ $current_user_new_conversations_this_month }}</span></p>
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

  const ctx4 = document.getElementById('Chart4');

  new Chart(ctx4, {
    type: 'line',
    data: {
      labels: @json($dates),
      datasets: [{
        responsive: true,
        label: 'All your message\'s per day',
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
<script>

  const ctx5 = document.getElementById('Chart5');

  new Chart(ctx5, {
    type: 'line',
    data: {
      labels: @json($dates),
      datasets: [{
        responsive: true,
        label: 'All your conversation\'s per day',
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
@endsection
				  
@section('script')
<script type="text/javascript">

</script>
@endsection
