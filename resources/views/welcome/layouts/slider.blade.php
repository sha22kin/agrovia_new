@php
  $marketPrices = $marketPrices ?? [];
  $topDemand = $topDemand ?? null;
  $firstPrice = $marketPrices[0] ?? null;
@endphp
<section class="hero">
  <div class="hero-bg" aria-hidden="true">
    <img src="{{asset('public/welcome/images/hero-farmer-app.webp')}}" alt="">
  </div>
  <div class="container">
    <div class="row hero-row">
      <div class="hero-copy">
        <h1>Empowering<br><span class="accent">Farmers.</span><br>Connecting<br><span class="accent">Markets.</span></h1>
        <p class="hero-desc">Buy, sell and grow together with Bangladesh's smart agriculture platform.</p>
        <div class="hero-ctas">
          <a href="{{route('register')}}" class="cta-primary">Get Started</a>
          <a href="{{route('pageView','marketplace')}}" class="cta-secondary">Explore Marketplace <span class="cta-arrow">→</span></a>
        </div>
        <div class="hero-community">
          <div class="avatars">
            <span>👨‍🌾</span><span>👩‍🌾</span><span>🧑‍🌾</span><span>👨‍🌾</span>
          </div>
          <p>Join 50,000+ farmers<br>and buyers today</p>
        </div>
      </div>

      <div class="hero-visual">
        <div class="hero-cards">
          <div class="hero-card" id="priceCard">
            <span class="stat-icon green" data-field="icon">{{$firstPrice['icon'] ?? '🌾'}}</span>
            <div class="stat-body">
              <div class="stat-label"><span class="live-dot"></span>Live Market Price</div>
              @if($firstPrice)
              <div class="stat-title" data-field="name">{{$firstPrice['name']}}@if($firstPrice['name_bn']) ({{$firstPrice['name_bn']}})@endif</div>
              <div class="stat-value"><span data-field="price">৳{{number_format($firstPrice['price'],2)}}</span> <small data-field="unit">/{{$firstPrice['unit']}}</small></div>
              <div class="stat-change {{($firstPrice['change'] ?? 0) < 0 ? 'down' : 'up'}}" data-field="change">
                @if($firstPrice['change'] !== null){{$firstPrice['change'] < 0 ? '↓' : '↑'}} {{abs($firstPrice['change'])}}%@endif
              </div>
              @else
              <div class="stat-title" data-field="name">Prices coming soon</div>
              <div class="stat-sub">Updated daily</div>
              @endif
            </div>
          </div>

          <div class="hero-card" id="weatherCard">
            <span class="stat-icon blue" data-field="icon">⛅</span>
            <div class="stat-body">
              <div class="stat-label">Weather Update</div>
              <div class="stat-value" data-field="temp">--° C</div>
              <div class="stat-sub" data-field="desc">Loading…</div>
            </div>
          </div>

          <div class="hero-card" id="demandCard">
            <span class="stat-icon orange">📈</span>
            <div class="stat-body">
              <div class="stat-label">High Demand</div>
              @if($topDemand)
              <div class="stat-title" data-field="name">{{$topDemand['name']}}</div>
              <div class="stat-change {{($topDemand['change'] ?? 0) < 0 ? 'down' : 'up'}}" data-field="change">
                @if($topDemand['change'] !== null){{$topDemand['change'] < 0 ? '↓' : '↑'}} {{abs($topDemand['change'])}}% @endif<span class="stat-sub">{{$topDemand['count']}} {{\Illuminate\Support\Str::plural('enquiry',$topDemand['count'])}} this week</span>
              </div>
              @else
              <div class="stat-title" data-field="name">No enquiries yet</div>
              <div class="stat-change" data-field="change"><span class="stat-sub">this week</span></div>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="row search-wrap">
      <div class="col">
        <form class="search-bar" id="heroSearch" action="{{route('pageView','marketplace')}}" method="GET">
          <div class="search-field">
            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" name="search" placeholder="What are you looking for?" aria-label="Search products">
            <select class="search-cat" name="category" aria-label="Category">
              <option value="">All Categories</option>
              @foreach(($category ?? collect())->whereNull('parent_id')->sortBy('name') as $ctg)
              <option value="{{$ctg->id}}">{{$ctg->name}}</option>
              @endforeach
            </select>
            <label class="search-loc" aria-label="Location">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="10" r="2.5"/></svg>
              <select name="location" id="heroLocation">
                <option value="">All Bangladesh</option>
                @foreach(($heroDivisions ?? collect()) as $div)
                <optgroup label="{{$div->name}} Division">
                  <option value="division-{{$div->id}}">All of {{$div->name}}</option>
                  @foreach($div->subDatas as $dist)
                  <option value="district-{{$dist->id}}">{{$dist->name}}</option>
                  @endforeach
                </optgroup>
                @endforeach
              </select>
            </label>
          </div>
          <button type="submit">Search</button>
        </form>
        <div class="popular">
          <span>Popular:</span>
          @foreach(['Paddy','Potato','Onion','Tomato','Maize','Fertilizer','Chicken'] as $tag)
          <button type="button" class="chip" data-search="{{$tag}}">{{$tag}}</button>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  var liveUrl = @json(route('marketLive'));
  var prices = @json($marketPrices);
  var priceIndex = 0;

  function esc(s){ var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }
  function money(n){ return '৳' + Number(n).toLocaleString('en-US',{minimumFractionDigits:2, maximumFractionDigits:2}); }
  function changeText(c){ return c === null ? '' : (c < 0 ? '↓ ' : '↑ ') + Math.abs(c) + '%'; }
  function label(p){ return p.name + (p.name_bn ? ' (' + p.name_bn + ')' : ''); }

  // Live price card: shows each published item in turn.
  function showPrice(){
    var card = document.getElementById('priceCard');
    if(!card || !prices.length) return;
    var p = prices[priceIndex % prices.length];
    var body = card.querySelector('.stat-body');
    card.querySelector('[data-field=icon]').textContent = p.icon;
    body.innerHTML = '<div class="stat-label"><span class="live-dot"></span>Live Market Price</div>'
      + '<div class="stat-title">' + esc(label(p)) + '</div>'
      + '<div class="stat-value">' + money(p.price) + ' <small>/' + esc(p.unit) + '</small></div>'
      + '<div class="stat-change ' + (p.change < 0 ? 'down' : 'up') + '">' + changeText(p.change) + '</div>';
    card.classList.remove('swap'); void card.offsetWidth; card.classList.add('swap');
  }

  function renderDemand(d){
    var card = document.getElementById('demandCard');
    if(!card) return;
    card.querySelector('[data-field=name]').textContent = d ? d.name : 'No enquiries yet';
    card.querySelector('[data-field=change]').className = 'stat-change ' + (d && d.change < 0 ? 'down' : 'up');
    card.querySelector('[data-field=change]').innerHTML = d
      ? (d.change === null ? '' : changeText(d.change) + ' ') + '<span class="stat-sub">' + d.count + (d.count === 1 ? ' enquiry' : ' enquiries') + ' this week</span>'
      : '<span class="stat-sub">this week</span>';
  }

  function refreshMarket(){
    fetch(liveUrl, {headers:{'Accept':'application/json'}})
      .then(function(r){ return r.ok ? r.json() : null; })
      .then(function(data){
        if(!data) return;
        prices = data.prices || [];
        renderDemand(data.demand);
        if(prices.length) showPrice();
      }).catch(function(){});
  }

  // Weather: Open-Meteo current conditions. Uses the visitor's location only if they already allowed it.
  var weatherCodes = {
    0:['☀️','Clear sky'], 1:['🌤️','Mainly clear'], 2:['⛅','Partly cloudy'], 3:['☁️','Overcast'],
    45:['🌫️','Foggy'], 48:['🌫️','Foggy'],
    51:['🌦️','Light drizzle'], 53:['🌦️','Drizzle'], 55:['🌦️','Heavy drizzle'], 56:['🌦️','Freezing drizzle'], 57:['🌦️','Freezing drizzle'],
    61:['🌧️','Light rain'], 63:['🌧️','Rain'], 65:['🌧️','Heavy rain'], 66:['🌧️','Freezing rain'], 67:['🌧️','Freezing rain'],
    71:['❄️','Light snow'], 73:['❄️','Snow'], 75:['❄️','Heavy snow'], 77:['❄️','Snow grains'],
    80:['🌦️','Rain showers'], 81:['🌦️','Rain showers'], 82:['⛈️','Heavy showers'],
    95:['⛈️','Thunderstorm'], 96:['⛈️','Thunderstorm'], 99:['⛈️','Thunderstorm']
  };
  var place = {lat:23.8103, lon:90.4125, name:'Dhaka'};

  function loadWeather(){
    var card = document.getElementById('weatherCard');
    if(!card) return;
    var url = 'https://api.open-meteo.com/v1/forecast?latitude=' + place.lat + '&longitude=' + place.lon
      + '&current=temperature_2m,relative_humidity_2m,weather_code&timezone=auto';
    fetch(url).then(function(r){ return r.json(); }).then(function(data){
      var c = data.current, w = weatherCodes[c.weather_code] || ['🌡️','—'];
      card.querySelector('[data-field=icon]').textContent = w[0];
      card.querySelector('[data-field=temp]').textContent = Math.round(c.temperature_2m) + '° C';
      card.querySelector('[data-field=desc]').textContent = w[1] + ' · ' + place.name;
      card.title = c.relative_humidity_2m + '% humidity';
    }).catch(function(){
      card.querySelector('[data-field=desc]').textContent = 'Weather unavailable';
    });
  }

  function useLocationIfAllowed(){
    if(!navigator.permissions || !navigator.geolocation) return;
    navigator.permissions.query({name:'geolocation'}).then(function(s){
      if(s.state !== 'granted') return;
      navigator.geolocation.getCurrentPosition(function(pos){
        place = {lat:pos.coords.latitude.toFixed(4), lon:pos.coords.longitude.toFixed(4), name:'Your area'};
        loadWeather();
      });
    }).catch(function(){});
  }

  // Search: remember the visitor's location; popular chips search right away.
  var searchForm = document.getElementById('heroSearch');
  var locSelect = document.getElementById('heroLocation');
  if(searchForm && locSelect){
    try {
      var saved = localStorage.getItem('agroLocation');
      if(saved && locSelect.querySelector('option[value="' + saved + '"]')) locSelect.value = saved;
    } catch(e) {}
    locSelect.addEventListener('change', function(){
      try { localStorage.setItem('agroLocation', locSelect.value); } catch(e) {}
    });
    searchForm.addEventListener('submit', function(){
      // Leave empty fields out of the URL.
      searchForm.querySelectorAll('input[name], select[name]').forEach(function(f){ f.disabled = !f.value; });
      setTimeout(function(){ searchForm.querySelectorAll('[disabled]').forEach(function(f){ f.disabled = false; }); }, 0);
    });
    document.querySelectorAll('.popular .chip[data-search]').forEach(function(chip){
      chip.addEventListener('click', function(){
        searchForm.querySelector('input[name=search]').value = chip.dataset.search;
        searchForm.requestSubmit ? searchForm.requestSubmit() : searchForm.submit();
      });
    });
  }

  loadWeather();
  useLocationIfAllowed();
  setInterval(loadWeather, 10 * 60 * 1000);
  setInterval(refreshMarket, 60 * 1000);
  setInterval(function(){ if(prices.length > 1){ priceIndex++; showPrice(); } }, 5000);
})();
</script>
