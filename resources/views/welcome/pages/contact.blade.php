@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{$page->seo_title?:websiteTitle($page->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$page->seo_title?:websiteTitle($page->name)}}" />
<meta name="description" property="og:description" content="{!!$page->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($page->image())}}" />
<meta name="url" property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('pageView',$page->slug?:'no-title')}}">
@endsection
@push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$page->name}}</h1>
    </div>
</div>

<section class="contact-page-sec">
    <div class="container">
        <h3>Get In Touch</h3>
        <div class="row mt-5">
            
            <div class="col-md-4">
                <div class="contact-info">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="contact-info-text">
                            <h2>Number</h2>
                            <span><a href="tel:{{general()->mobile}}">{{general()->mobile}}</a></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="contact-info middle">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <div class="contact-info-text">
                            <h2>address</h2>
                            <span><a href="#">{{general()->address_one}}</a></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="contact-info">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="contact-info-text">
                            <h2>E-mail</h2>
                            <span><a href="tel:{{general()->email}}">{{general()->email}}</a></span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="contactContent">
            <h4>
                Have questions, suggestions, or need assistance with your ads? We're here to help! Reach out to our team using the contact details below: at {{general()->email}}.
            </h4>
        </div>
    </div>
</section>

<div class="formPart">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="formContent">
                    Or, simply fill out our contact form on the website, and one of our representatives will get back to you as soon as possible.
                </p>
            </div>
            <div class="col-md-6">
                <div class="contact-page-form" method="post">
                    <h2>Contact Us</h2>
                    <hr>
                    @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <strong>Success! </strong> {{Session::get('success')}}.
                    </div>
                    @endif
                    <form action="{{route('contactMail')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-12">
                                @if ($errors->has('name'))
                                <p>{{ $errors->first('name') }}</p>
                                @endif
                                <input type="text" placeholder="Name" name="name" />
                            </div>
                            <div class="col-md-12 col-12">
                                @if ($errors->has('message'))
                                <p>{{ $errors->first('message') }}</p>
                                @endif
                                <input type="text" placeholder="Description" name="message" />
                            </div>
                            <div class="col-md-6 col-12">
                                @if ($errors->has('email'))
                                <p>{{ $errors->first('email') }}</p>
                                @endif
                                <input type="email" placeholder="Email" name="email" required />
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="single-input-field">
                                    @if ($errors->has('phone'))
                                    <p>{{ $errors->first('phone') }}</p>
                                    @endif
                                    <input type="text" placeholder="Phone No." name="phone" />
                                </div>
                            </div>
                            
                            <div class="single-input-fieldsbtn">
                                <input type="submit" value="Submit" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="blanKpart"></div>

<div class="locationCon">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d233667.49930322517!2d90.25487367081577!3d23.781067238446077!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b087026b81%3A0x8fa563bbdd5904c2!2sDhaka!5e0!3m2!1sen!2sbd!4v1733913619130!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

@endsection 

@push('js') 

@endpush


