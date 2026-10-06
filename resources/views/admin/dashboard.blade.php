@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Dashboard')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')
<div class="content-header row"></div>
<div class="content-body">
    <!-- Grouped multiple cards for statistics starts here -->
    <div class="row grouped-multiple-statistics-card">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                            <div class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">
                                <span class="card-icon primary d-flex justify-content-center mr-3">
                                    <i class="fas fa-stream customize-icon font-large-2 p-1"></i>
                                </span>
                                <div class="stats-amount mr-3">
                                    <h3 class="heading-text text-bold-600">{{$reports['services']}}</h3>
                                    <p class="sub-heading">Services</p>
                                </div>
                                <span class="inc-dec-percentage">
                                    <small class="info"><i class="fa fa-arrow-up"></i> Totals</small>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                            <div class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">
                                <span class="card-icon danger d-flex justify-content-center mr-3">
                                    <i class="fas fa-sitemap customize-icon font-large-2 p-1"></i>
                                </span>
                                <div class="stats-amount mr-3">
                                    <h3 class="heading-text text-bold-600">{{$reports['posts']}}</h3>
                                    <p class="sub-heading">Posts</p>
                                </div>
                                <span class="inc-dec-percentage">
                                    <small class="success"><i class="fa fa-arrow-up"></i> 30 Days </small>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                            <div class="d-flex align-items-start border-right-blue-grey border-right-lighten-5">
                                <span class="card-icon success d-flex justify-content-center mr-3">
                                    <i class="fas fa-users customize-icon font-large-2 p-1"></i>
                                </span>
                                <div class="stats-amount mr-3">
                                    <h3 class="heading-text text-bold-600">{{$reports['pages']}}</h3>
                                    <p class="sub-heading">Pages</p>
                                </div>
                                <span class="inc-dec-percentage">
                                    <small class="primary"><i class="fa fa-arrow-up"></i> Totals </small>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                            <div class="d-flex align-items-start">
                                <span class="card-icon warning d-flex justify-content-center mr-3">
                                    <i class="fa fa-money customize-icon font-large-2 p-1"></i>
                                </span>
                                <div class="stats-amount mr-3">
                                    <h3 class="heading-text text-bold-600">{{$reports['users']}}</h3>
                                    <p class="sub-heading">Sales</p>
                                </div>
                                <span class="inc-dec-percentage">
                                    <small class="success"><i class="fa fa-arrow-up"></i> 30 Days</small>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Grouped multiple cards for statistics ends here -->
</div>
@endsection @push('js')
<script>
    $(document).ready(function () {
        // function reloadLocation() {
        //     location.reload();
        // }
        // Set a timeout to reload after 1 minute
        // setTimeout(function() {
        //         reloadLocation();
        //     }, 3000);
    });
</script>
@endpush