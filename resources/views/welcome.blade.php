@extends('layouts.common')
@section('content')
<section id="hero-home"class="hero is-medium is-primary is-bold">
    <div class="overlay"></div>
    <div class="hero-body">
        <div class="container">
            <h1 class="title" style="font-family: 'Changa One', cursive;">
                InteracSMS Nigeria
            </h1>
            <div class="hero-property">
                <h2 class="subtitle">
                    Faster way to send multiple messages to People all across Nigeria.<br>
                    Simple and easy for Everyone.
                </h2>

                <div style="text-align:center;">
                    <a id="gs-btn" style="font-size: 1rem;" class="is-medium button" href={{ Auth::check() ? '/home':'/auth' }}>{{ Auth::check() ? "Return To Dashboard" : "Get Started" }}</a>
                </div>

            </div>
        </div>
    </div>
</section>
<section style="background: #f5f5f54f;" id="home-1-el" class="section is-small is-fullheight">
    <div class="container">
        <div class="columns">
            <div class="column">
                <div class="content">
                    <p class="info-subtitle">
                            InteracSMS serves as Nigeria's trusted service provider in terms of quality SMS delivery to a diverse range of consumers cutting across dfferent sectors.
                            <br>
                            <br>
                            Our platform supports transactional, announcments campaigns and verification messages - which can be used by your clients in satisfying the needs of your clients, spanning friends, organizations, work colleagues, employees and many more.
                            <br>
                            <br>
                            Using bulk SMS in Nigeria, "With 186 million inhabitants", is an added advantage to all the bulk SMS service users, especially users who required Bulk SMS service for business purposes. The needs for Bulk SMS service in our day-to-day activities have necessitated the global acceptance of text message. The best online, as well as an offline marketing tool of the present 21st century, is Text Message, which is also referred to as, SMS.
                    </p>
                </div>
                {{--  <div class="is-divider" data-content="  "></div>  --}}
            </div>
        </div>

        <div class="container" style="padding: 0 10%;">
            <div class="columns">
                <div class="column is-4 features">
                    <div class="card">
                        <div class="card-image" style="padding: 20px 0;">
                            <figure style="margin: 0 auto;" class="image is-128x128">
                            <img style="margin-top: 1.5rem;" src="{{ asset('img/home/svgs/cross-platform.svg') }}" alt="Interac SMS">
                            </figure>
                        </div>
                        <div class="card-content">
                            <h4 class="title has-text-centered">USER EXPERIENCE</h4>
                            <div class="is-divider" data-content="  "></div>
                            <p class="subtitle">
                                You can access full features of InteracSMS on all devices assuring you of uninterrupted service.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="column is-4 features">
                    <div class="card">
                        <div class="card-image" style="padding: 20px 0;">
                            <figure style="margin: 0 auto;" class="image is-128x128">
                            <img style="margin-top: 1.5rem;" src="{{ asset('img/home/svgs/discount.svg') }}" alt="Interac SMS">
                            </figure>
                        </div>
                        <div class="card-content">
                            <h4 class="title has-text-centered">
                                    CHEAP SMS RATES
                            </h4>
                            <div class="is-divider" data-content="  "></div>
                            <p class="subtitle">
                                We offer cheap rates for quality SMS service, enabling you to accomodate bigger demands from your clients without panic.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="column is-4 features">
                    <div class="card">
                        <div class="card-image" style="padding: 20px 0;">
                            <figure style="margin: 0 auto;" class="image is-128x128">
                            <img style="margin-top: 1.5rem;" src="{{ asset('img/home/svgs/credit-cards-payment.svg') }}" alt="Interac SMS">
                            </figure>
                        </div>
                        <div class="card-content">
                            <h4 class="title has-text-centered">
                                    EASE OF PAYMENT
                            </h4>
                            <div class="is-divider" data-content="  "></div>
                            <p class="subtitle">
                                    With our multiple payment platforms both offline and online, you are assured of ease of payment for SMS units without hassle.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-4 features">
                    <div class="card">
                        <div class="card-image" style="padding: 20px 0;">
                            <figure style="margin: 0 auto;" class="image is-128x128">
                            <img style="margin-top: 1.5rem;" src="{{ asset('img/home/svgs/delivery-report.svg') }}" alt="Interac SMS">
                            </figure>
                        </div>
                        <div class="card-content">
                            <h4 class="title has-text-centered">DELIVERY REPORTS</h4>
                            <div class="is-divider" data-content="  "></div>
                            <p class="subtitle">
                                Get delivery reports on your messages within minutes, ensuring your are satisfied with the insights on your campaign.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="column is-4 features">
                    <div class="card">
                        <div class="card-image" style="padding: 20px 0;">
                            <figure style="margin: 0 auto;" class="image is-128x128">
                            <img style="margin-top: 1.5rem;" src="{{ asset('img/home/svgs/speedometer.svg') }}" alt="Interac SMS">
                            </figure>
                        </div>
                        <div class="card-content">
                            <h4 class="title has-text-centered">
                                    FAST SMS DELIVERIES
                            </h4>
                            <div class="is-divider" data-content="  "></div>
                            <p class="subtitle">
                                    Ultra fast deliveries on our multiple routes ensure that your messages get delivered promptly without any form of delay.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="column is-4 features">
                    <div class="card">
                        <div class="card-image" style="padding: 20px 0;">
                            <figure style="margin: 0 auto;" class="image is-128x128">
                            <img style="margin-top: 1.5rem;" src="{{ asset('img/home/svgs/support.svg') }}" alt="Interac SMS">
                            </figure>
                        </div>
                        <div class="card-content">
                            <h4 class="title has-text-centered">
                                    24/7 CUSTOMER SUPPORT
                            </h4>
                            <div class="is-divider" data-content="  "></div>
                            <p class="subtitle">
                                    Our support channels, including live Support and phone lines are available to your respond to issues in real-time.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="hero is-medium is-bold" style="background: #9ab2c712">
    <div class="hero-body">
        <h1 class="has-text-centered" style="margin: 40px 0;">Pricing Bundles</h1>
        <div class="contiainer" style="padding: 0 10%">
            <div class="pricing-table">
                <div class="pricing-plan">
                    <div class="plan-header">Standard</div>
                    <div class="plan-price"><span class="plan-price-amount"><span class="plan-price-currency"></span>&#8358;2.2</span> per unit</div>
                    <div class="plan-items">
                    <div class="plan-item">50 - 999 Units</div>
                    <div class="plan-item">100 Domains</div>
                    <div class="plan-item">-</div>
                    <div class="plan-item">-</div>
                    </div>
                    <div class="plan-footer">
                        <a href="/home?pricing=w" class="button is-fullwidth" >Choose</a>
                    </div>
                </div>

                <div class="pricing-plan is-warning">
                    <div class="plan-header">Business</div>
                    <div class="plan-price"><span class="plan-price-amount"><span class="plan-price-currency"></span>&#8358;2.0</span> per unit</div>
                    <div class="plan-items">
                    <div class="plan-item">1,000 - 9,999 Units</div>
                    <div class="plan-item">25 Domains</div>
                    <div class="plan-item">1TB Bandwidth</div>
                    <div class="plan-item">-</div>
                    </div>
                    <div class="plan-footer">
                    <a href="/home?pricing=w" class="button is-fullwidth">Choose</a>
                    </div>
                </div>

                <div class="pricing-plan">
                    <div class="plan-header">Campaign</div>
                    <div class="plan-price"><span style="color:#00d1b2" class="plan-price-amount"><span class="plan-price-currency"></span>&#8358;1.85</span> per unit</div>
                    <div class="plan-items">
                    <div class="plan-item">10,000 - 49,999 Units</div>
                    <div class="plan-item">50 Domains</div>
                    <div class="plan-item">1TB Bandwidth</div>
                    <div class="plan-item">100 Email Boxes</div>
                    </div>
                    <div class="plan-footer">
                    <a href="/home?pricing=w" class="button is-primary is-fullwidth">Choose</a>
                    </div>
                </div>

                <div class="pricing-plan is-danger">
                    <div class="plan-header">Professional</div>
                    <div class="plan-price"><span class="plan-price-amount"><span class="plan-price-currency"></span>&#8358;1.7</span> per unit</div>
                    <div class="plan-items">
                    <div class="plan-item">Above 50,000 Units</div>
                    <div class="plan-item">100 Domains</div>
                    <div class="plan-item">1TB Bandwidth</div>
                    <div class="plan-item">1000 Email Boxes</div>
                    </div>
                    <div class="plan-footer">
                    <a href="/home?pricing=w" class="button is-fullwidth">Choose</a>
                    </div>
                </div>
                </div>
        </div>
    </div>
</section>
<section class="hero is-medium is-bold">
    <div class="hero-body faq">
        <h1 class="has-text-centered" style="margin: 40px 0;">Frequently Asked Questions</h1>
        <div class="container" style="padding: 0 10%;">
                <div class="toggle">
                    <div class="toggle-title ">
                        <h3>
                        <i></i>
                        <span class="title-name">What is DND?</span>
                        </h3>
                    </div>
                    <div class="toggle-inner">
                        <p>DND stands for Do-Not-Disturb. A service introduced by Nigerian Communication Commission (NCC) that enables mobile subscribers to opt out of marketing/promotional messages of which bulk SMS is classified as one. Hence, mobile numbers on the DND list are not able to receive bulk SMS via the regular route.

                        To deliver to DND numbers, we make use of a Corporate Route. The messages are sent to the numbers provided, then the phone numbers blocked by the network providers will be retried over the Corporate Route. <br>
                        However, you can specify that all messages be delivered over the Corporate Route, <strong> <i>please note that this will attract double the units required to send the messages. </i> </strong>
                        <br>
                        Please, note that the Sender ID could be changed for only DND numbers. <br>

                        Text 'STATUS' to '2442' to check your DND status (for all networks in Nigeria). <br>
                        To opt out of DND, <br>
                        on MTN text 'ALLOW' to '2442', <br>
                        on GLO text 'CANCEL' to '2442', <br>
                        on Etisalat text 'START' to '2442', <br>
                        on Airtel text 'ALLOW' to '2442'</p>
                    </div>
                </div><!-- END OF TOGGLE -->
                <div class="toggle">
                    <div class="toggle-title ">
                        <h3>
                        <i></i>
                        <span class="title-name">How can I Purchase Units?</span>
                        </h3>
                    </div>
                    <div class="toggle-inner">
                        <p>
                            Units are purchased through our 🔐 secure online payment gateway where with you can pay via your debit cards 💳 or bank accounts. <br>
                            This ensures that your account is funded swiftly to enable you send messages without the hassle of our banks 🏦 and their queues. <br> <br>
                            Cash deposits can also be paid at our office @ 276b Corporation Drive, Dolphin Estate, Ikoyi, Lagos.
                        </p>
                    </div>
                </div><!-- END OF TOGGLE -->
                <div class="toggle">
                    <div class="toggle-title ">
                        <h3>
                        <i></i>
                        <span class="title-name">Do our message units expire? - Our answer is never 🤭</span>
                        </h3>
                    </div>
                    <div class="toggle-inner">
                        <p>
                            Units purchased on InteracSMS do not expire and <br> would be available 1,000,000,000,000 years from now. <br> <br>

                            We are 🤴Interac🦁, we keep your units 💰💰💰.
                        </p>
                    </div>
                </div><!-- END OF TOGGLE -->
                <div class="toggle">
                    <div class="toggle-title ">
                        <h3>
                        <i></i>
                        <span class="title-name">Quick Maths: How many 🔢 characters is equal to a unit? 😂😂😂</span>
                        </h3>
                    </div>
                    <div class="toggle-inner">
                        <p>
                            There are 160 characters per SMS message 📃, which translates to a 💰 Unit. <br> If you decide to send up to 🔢 320 characters, <br> that will resolve into 2 pages of SMS and will cost you 2 💰 Units in turn per recipient 😄.
                        </p>
                    </div>
                </div><!-- END OF TOGGLE -->
                <div class="toggle">
                    <div class="toggle-title ">
                        <h3>
                        <i></i>
                        <span class="title-name">How do I send messages on InteracSMS?</span>
                        </h3>
                    </div>
                    <div class="toggle-inner">
                        <p>
                            First step is to have sufficient credit for the amount of messages to be sent. 😎<br> <br>

                            Once that is covered, the next step is to settile on the Sender ID to be used. <br> <br>

                            What is sender ID ❓ <br>
                            📓 Sender ID is the customize name you want your message to deliver as on the recipient line. <br> For Example: 💑 TUNDEOPE18 💑, ☂️ PDP ⛱️, 🐵 LasisiElenu 🙈, 🇳🇬 Buhari 🇳🇬, 💰 30BG 💲, and so on. <br>
                            NOTE: The sender ID can only contain 11 characters at maximum. Don't try your luck, we are watching you.
                            <br> <br>

                            Next Step: The Message Type <br>
                            As you know, SMS is supported by almost all mobile devices and mobile networks (GSM and others) in the world. An SMS message can contain a short text only, and can be sent to any cellphone number around the world. SMS messages are routed internationally. <br> <strong> PLAIN SMS </strong> Plain SMS can contain standard text format, its standard length is 160 characters. <br> <strong> FLASH SMS </strong> Flash SMS is also called Class0 SMS. It display only SMS. On most mobile phones it is displayed immediately and is never saved to the SIM card or your phone memory without your prior permission. Don’t change this option except you know what you’re doing. <br> <br>

                            The Recipients: <br>
                            This is the column where you type or paste the phone numbers you wish to forward messages to. The number must be separated by (a comma, space or putting each number on a line). <br>
                            Also beside the Recipients column, you can also select contacts from your existing phone book which has been saved on the website. <br>
                            Finally you can upload a text(.txt) or a (.csv) file which have been perfectly separated as recipients by line, this is very important. Please note that you can use all or any of the available options to add your recipients. <br> Please note that any other file formats such as csv are not supported and will give an error. <br> <br>

                            Next: Messages <br> This is the simplest part.<i> The maximum number of characters per page of SMS message is 160 characters. </i> <br>

                            Lastly, Scheduling Messages: <br>
                            After clicking the "SEND MESSAGE" button, a metrics report on the message you want to send would be generated and you can switch over tot the overview tab to include a scheduled date. <br> By default none will be entered for you. <br>
                            You don’t really want to miss wishing your loved ones on special occasion like: birthdays, religious celebrations etc. do you?  Hence, you can prepare for such events with this option;
                        </p>
                    </div>
                </div><!-- END OF TOGGLE -->
        </div>
    </div>
</section>
<footer class="footer ">
    <div class="container" style="padding: 0 10%;">
        <div class="columns is-centered">
            <div class="column is-4">
                    <a href="/auth?register=1"><h3 class="title has-text-centered" style="font-size: 1.5rem;font-weight: 600;line-height: 1.125;">Ready for InteracSMS?</h3></a>
                    <p class="has-text-centered" style="margin-bottom:1rem">
                        Sign up in less than a minute and get 10 free units to sending messages immediately.
                    </p>
                    <div>
                        <a class="cta-btn is-medium button" href="/auth?register=1">SIGN UP NOW</a>
                    </div>
            </div>
        </div>
        <div class="is-divider" data-content=""></div>
        <div class="columns">
            <div class="column is-8">
                    <img src="img/interacsms.png" alt="Interactive Sms system also know as InteracSms, is built to meeting client's demand to engaging its users via their mobile using their local Sms system." width="100" height="50">
                    <p>
                        2013-2018 © InteracSMS.com is a owned and managed by ROI Digital Limited. All rights reserved.
                    </p>
            </div>
        </div>
    </div>
</footer>
@endsection
@section('scripts')
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    if( $(".toggle .toggle-title").hasClass('active') ){
        $(".toggle .toggle-title.active").closest('.toggle').find('.toggle-inner').show();
    }
    $(".toggle .toggle-title").click(function(){
        if( $(this).hasClass('active') ){
            $(this).removeClass("active").closest('.toggle').find('.toggle-inner').slideUp(200);
        }
        else{
            $(this).addClass("active").closest('.toggle').find('.toggle-inner').slideDown(200);
        }
    });

    // Get all "navbar-burger" elements
    var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    console.log($navbarBurgers);
    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {

        // Add a click event on each of them
        $navbarBurgers.forEach(function ($el) {
            $el.addEventListener('click', function () {

                // Get the target from the "data-target" attribute
                var target = $el.dataset.target;
                var $target = document.getElementById(target);

                // Toggle the class on both the "navbar-burger" and the "navbar-menu"
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');

            });
        });
    }

    });
</script>
@endsection
