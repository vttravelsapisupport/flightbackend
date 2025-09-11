<div class="card border-0 shadow-sm rounded" style="width: 17rem; border-radius:.25rem!important;">
   <div class="card-body p-1 rounded" style="background-color: #d9fdd3;">
      <div class="card border-0 rounded img-rounded">
         <div class="card-body">
            <img src="https://admin-v2.goflysmart.com/images/gfs-logo-full-compact.jpg" class="card-img-top">
         </div>
      </div>
      <div class="text-left p-2">
        <b>VishalTravels Series Air-Fare Updates</b>✈️
        <br><br>
        ✔️ Book Online
        <br>
        ✔️ Live PNR
        <br>
        🌐 <b><a href="https://vishaltravels.in" target="agent_tab">vishaltravels.in</a></b>
        <br>
        =====================
        @foreach($output_datas as $i => $d)
        @php if (count($d) != request()->query('days')) continue; @endphp
        <br>
        ✈ <b>{{ $i }}</b>
        <br>
        @foreach($d as $x => $y)
        @php
            $date = Carbon\Carbon::parse($y->travel_date)->format('d M');
        @endphp
        ▪ {{ $date }} - ₹ {{ number_format($y->sell_price, 0, '.', ',') }}/-
        <br>
        @endforeach
        @endforeach
        =====================
        <br>
        <b><a href="https://vishaltravels.in" target="gfs_tab">vishaltravels</a></b>
        <br><br>
        <b>Call & WhatsApp</b>
        <br>
        <a href="tel:07969244000">07969244000</a>
        <br><br>
        <a href="https://wa.me/919978922077" target="whatsapp_tab">wa.me/919978922077</a>
        <br>
        =====================
         <div class="offset-10 pt-0">
            <div class="row d-flex align-items-center justify-content-center">
            {{ \Carbon\Carbon::now()->tz('Asia/Kolkata')->format('H:m') }}&nbsp;
                <svg viewBox="0 0 16 11" height="11" width="16" preserveAspectRatio="xMidYMid meet" class="" fill="none" color="#5bc1ea">
                    <path d="M11.0714 0.652832C10.991 0.585124 10.8894 0.55127 10.7667 0.55127C10.6186 0.55127 10.4916 0.610514 10.3858 0.729004L4.19688 8.36523L1.79112 6.09277C1.7488 6.04622 1.69802 6.01025 1.63877 5.98486C1.57953 5.95947 1.51817 5.94678 1.45469 5.94678C1.32351 5.94678 1.20925 5.99544 1.11192 6.09277L0.800883 6.40381C0.707784 6.49268 0.661235 6.60482 0.661235 6.74023C0.661235 6.87565 0.707784 6.98991 0.800883 7.08301L3.79698 10.0791C3.94509 10.2145 4.11224 10.2822 4.29844 10.2822C4.40424 10.2822 4.5058 10.259 4.60313 10.2124C4.70046 10.1659 4.78086 10.1003 4.84434 10.0156L11.4903 1.59863C11.5623 1.5013 11.5982 1.40186 11.5982 1.30029C11.5982 1.14372 11.5348 1.01888 11.4078 0.925781L11.0714 0.652832ZM8.6212 8.32715C8.43077 8.20866 8.2488 8.09017 8.0753 7.97168C7.99489 7.89128 7.8891 7.85107 7.75791 7.85107C7.6098 7.85107 7.4892 7.90397 7.3961 8.00977L7.10411 8.33984C7.01947 8.43717 6.97715 8.54508 6.97715 8.66357C6.97715 8.79476 7.0237 8.90902 7.1168 9.00635L8.1959 10.0791C8.33132 10.2145 8.49636 10.2822 8.69102 10.2822C8.79681 10.2822 8.89838 10.259 8.99571 10.2124C9.09304 10.1659 9.17556 10.1003 9.24327 10.0156L15.8639 1.62402C15.9358 1.53939 15.9718 1.43994 15.9718 1.32568C15.9718 1.1818 15.9125 1.05697 15.794 0.951172L15.4386 0.678223C15.3582 0.610514 15.2587 0.57666 15.1402 0.57666C14.9964 0.57666 14.8715 0.635905 14.7657 0.754395L8.6212 8.32715Z" fill="currentColor"></path>
                </svg>
            </div>
         </div>
      </div>
      <hr class="m-0 p-0">
      <div class="d-flex align-items-center justify-content-center m-3">
        <a href="https://agent.goflysmart.com/" target="agent_tab" style="text-decoration: none; color:#009de2;">
            <span data-testid="hsm-link" data-icon="hsm-link">
                <svg viewBox="0 0 19 18" height="18" width="19" preserveAspectRatio="xMidYMid meet" class="" version="1.1">
                    <path d="M14,5.41421356 L9.70710678,9.70710678 C9.31658249,10.0976311 8.68341751,10.0976311 8.29289322,9.70710678 C7.90236893,9.31658249 7.90236893,8.68341751 8.29289322,8.29289322 L12.5857864,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,8 C16,8.55228475 15.5522847,9 15,9 C14.4477153,9 14,8.55228475 14,8 L14,5.41421356 Z M14,12 C14,11.4477153 14.4477153,11 15,11 C15.5522847,11 16,11.4477153 16,12 L16,13 C16,14.6568542 14.6568542,16 13,16 L5,16 C3.34314575,16 2,14.6568542 2,13 L2,5 C2,3.34314575 3.34314575,2 5,2 L6,2 C6.55228475,2 7,2.44771525 7,3 C7,3.55228475 6.55228475,4 6,4 L5,4 C4.44771525,4 4,4.44771525 4,5 L4,13 C4,13.5522847 4.44771525,14 5,14 L13,14 C13.5522847,14 14,13.5522847 14,13 L14,12 Z" fill="currentColor" fill-rule="nonzero"></path>
                </svg>
            </span>
            Book Now
        </a>
    </div>
   </div>
</div>