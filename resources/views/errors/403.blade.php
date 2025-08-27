
@section('content')
<div class="container text-center py-5">
    <h1 class="display-3 text-danger">403</h1>
    <p class="lead">Access Denied. You do not have permission to view this page.</p>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Go Back</a>
</div>
@endsection
