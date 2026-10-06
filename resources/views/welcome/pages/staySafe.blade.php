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

<div class="staySafePage">
    <div class="container">
        <div class="careerFirst mt-0">
            <h2><span>Stay Safe</span> on FSBS</h2>
            <p>
                At FSBS, we are fully committed to ensuring that your experience on our platform is safe and secure. Here are some essential tips for safe transactions on FSBS.
            </p>
        </div>
        <hr>
        <div class="otherContentParts">
            <div class="row">
                <div class="col-md-12">
                    <h4>General Safety Advice</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            1. Verify Items in Person
                            <p>
                                Always meet the seller in person and inspect the industrial item thoroughly before making any payment. This helps you confirm the item’s condition and functionality
                            </p>
                        </li>
                        <li>
                            2. Exchange Items and Payment at the Same Time
                            <p>
                                <b>For Buyers:</b> Don’t make any payments before physically receiving the item.
                            </p>
                            <p>
                                <b>For Sellers:</b> Don’t send an item until payment is fully received.
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            3. Use Common Sense
                            <p>
                                If a deal seems too good to be true (e.g., unusually low prices or quick money offers), it might be a scam. Always proceed cautiously.
                            </p>
                        </li>
                        
                        <li>
                            4.  Never Share Financial Information
                            <p>
                                Avoid giving out sensitive information such as bank account details or payment platform information (e.g., PayPal).
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>
        <div class="otherContentParts">
            <div class="row">
                <div class="col-md-12">
                    <h4>Scams and Frauds to Watch Out For</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            1. Verify Items in Person
                            <p>
                                FSBS does not provide any payment schemes or protection services. Be cautious of emails claiming to offer payment protection through FSBS. Report such emails immediately.
                            </p>
                        </li>
                        <li>
                            2. Fake Information Requests
                            <p>
                                FSBS will never send emails requesting your personal or account details. If you receive any suspicious emails, do not click on any links. Report the email and delete it.
                            </p>
                        </li>
                        <li>
                            3. Fake Fee Requests
                            <p>
                                Avoid anyone who requests additional fees to buy or sell an item on FSBS. FSBS does not charge for its basic services and does not permit listings for items located outside Bangladesh, so additional fees (like import or brokerage) are not required.
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            4. Requests for Money Transfer Services
                            <p>
                                Avoid using money transfer services like Western Union or MoneyGram, as these are not recommended for transactions with strangers and are often used in scams. Stick to secure payment methods.
                            </p>
                        </li>
                        
                        <li>
                            5. No Delivery Services on FSBS
                            <p>
                                FSBS does not provide delivery services. Be cautious of any false claims regarding delivery options and avoid making payments for unverified delivery arrangements.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>
        <div class="otherContentParts">
            <div class="row">
                <div class="col-md-12">
                    <h4>Safety Measures Provided by FSBS</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            1. Email Protection
                            <p>
                                Your email address will not be displayed on your ad, protecting you from unwanted spam or phishing attempts.
                            </p>
                        </li>
                        <li>
                            2. hone Number Privacy Option
                            <p>
                                You can choose to hide your phone number on your ad and still be contacted via FSBS chat, enhancing your privacy
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            3. Ongoing Technology Improvements
                            <p>
                                FSBS continuously upgrades its technology to detect and prevent suspicious or inappropriate activity on the platform.
                            </p>
                        </li>
                        
                        <li>
                            4. Blocking Repeat Offenders
                            <p>
                                FSBS tracks reports of suspicious or illegal activity and takes action to prevent repeat offenders from using the platform.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>
        <div class="otherContentParts">
            <div class="row">
                <div class="col-md-12">
                    <h4>Reporting a Safety Issue</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            1. Victim of a Scam?
                            <p>
                                If you believe you've been scammed, report the situation to FSBS immediately. We also recommend contacting your local police department to ensure the issue is addressed properly.
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            2. Information Sharing and Privacy
                            <p>
                                FSBS prioritizes user privacy and does not share user information publicly. However, we take safety seriously and will cooperate with law enforcement if required for investigations into fraudulent or criminal activity.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 
@push('js') 
@endpush