@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Expert Dashboard')}}</title>
@endsection

@section('contents')
<div class="container-fluid px-0">
  {{-- ==================== ROW 1: Expert Profile Banner ==================== --}}
  <div class="card frest-gamification-card mb-4">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div class="av-avatar-wrap">
            <img src="{{ $user->avatar(200) }}" alt="{{ $user->name }}">
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <h4 class="gamification-title mb-0">{{ $user->name }}</h4>
              <span class="av-verified-badge">
                @if($profile?->isVerified())
                  <i class="bx bxs-check-shield"></i> Verified Expert
                @else
                  <i class="bx bx-award"></i> Expert
                @endif
              </span>
            </div>
            <div class="meta-line mt-1">
              <i class="bx bx-book-reader"></i>
              @if($profile?->headline)
                <span>{{ $profile->headline }}</span>
              @else
                <a href="{{ route('expert.profile') }}" class="text-primary text-decoration-underline">Add your title and specializations</a>
              @endif
            </div>
            <div class="meta-line mt-1">
              <i class="bx bx-map-pin"></i> <span>{{ $location }}</span>
              <span class="mx-1">·</span>
              <i class="bx bx-calendar"></i> <span>Expert since {{ ($profile?->created_at ?: $user->created_at)?->format('M Y') }}</span>
            </div>
          </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('expert.availability') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-time-five me-1"></i> Set Availability
          </a>
          <a href="{{ route('expert.profile') }}" class="btn btn-outline-primary btn-sm">
            <i class="bx bx-user me-1"></i> Edit Profile
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== 5 Frest Metric Stat Cards ==================== --}}
  <div class="row g-3 mb-4">
    <!-- Metric 1: Consultations -->
    <div class="col-xl col-md-4 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-primary">
            <i class="bx bx-calendar-check"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Consultations</div>
            <div class="stat-value">{{ number_format($stats['consultations']) }}</div>
            <div class="stat-sub text-primary">
              <i class="bx bx-user-voice"></i> Total Sessions
            </div>
          </div>
          <a href="{{ route('expert.consultations') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 2: Upcoming Sessions -->
    <div class="col-xl col-md-4 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-info">
            <i class="bx bx-time-five"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Upcoming</div>
            <div class="stat-value">{{ number_format($stats['upcoming']) }}</div>
            <div class="stat-sub text-info">
              <i class="bx bx-calendar-event"></i> Schedule
            </div>
          </div>
          <a href="{{ route('expert.consultations') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 3: Total Earnings -->
    <div class="col-xl col-md-4 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-success">
            <i class="bx bx-wallet"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Total Earnings</div>
            <div class="stat-value">{{ $currency }} {{ number_format($stats['earnings']) }}</div>
            <div class="stat-sub text-success">
              <i class="bx bx-trending-up"></i> Revenue
            </div>
          </div>
          <a href="{{ route('expert.earnings') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 4: Articles Published -->
    <div class="col-xl col-md-6 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-warning">
            <i class="bx bx-news"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Articles</div>
            <div class="stat-value">{{ number_format($stats['articles']) }}</div>
            <div class="stat-sub text-warning">
              <i class="bx bx-edit-alt"></i> Publications
            </div>
          </div>
          <a href="{{ route('expert.articles') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 5: Rating -->
    <div class="col-xl col-md-6 col-sm-12 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-danger">
            <i class="bx bxs-star"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Expert Rating</div>
            <div class="stat-value">{{ $stats['rating'] ? $stats['rating'].' ★' : '—' }}</div>
            <div class="stat-sub text-danger">
              <i class="bx bx-star"></i> {{ $stats['reviews'] ? 'Reviews Active' : 'No Reviews' }}
            </div>
          </div>
          @if($stats['reviews'])
            <a href="{{ route('expert.reviews') }}" class="btn btn-sm btn-outline-primary">View</a>
          @else
            <span class="btn btn-sm btn-outline-primary disabled">View</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== ROW 2: Consultations, Q&A & Sidebar Cards ==================== --}}
  <div class="row g-4">
    {{-- Left Main Column: Consultations & Q&A --}}
    <div class="col-lg-8">
      <!-- Upcoming Consultations Card -->
      <div class="card mb-4">
        <div class="card-header pb-2 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="bx bx-calendar-check text-primary"></i> Upcoming Consultations
          </h5>
          <a href="{{ route('expert.consultations') }}" class="av-link-more">
            View Schedule <i class="bx bx-chevron-right"></i>
          </a>
        </div>
        <div class="card-body pt-2">
          @forelse($upcoming as $consultation)
            <div class="av-consult-row">
              <img class="av-consult-avatar" src="{{ $consultation->farmer?->avatar(80) }}" alt="{{ $consultation->farmer?->name }}">
              <div class="flex-grow-1 overflow-hidden">
                <div class="consult-title-text">{{ $consultation->farmer?->name ?: 'Farmer' }} <span class="text-muted fw-normal">· {{ $consultation->topic }}</span></div>
                <div class="consult-sub-text">{{ $consultation->modeLabel() }} · {{ $consultation->whenLabel() }}</div>
              </div>
              <div class="d-flex align-items-center gap-2 flex-shrink-0">
                @if($consultation->meeting_link)
                  <a href="{{ $consultation->meeting_link }}" target="_blank" rel="noopener" class="btn btn-sm btn-primary">Join</a>
                @endif
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal"
                  data-id="{{ $consultation->id }}" data-topic="{{ $consultation->topic }}" data-when="{{ $consultation->scheduled_at?->format('Y-m-d\TH:i') }}">
                  Reschedule
                </button>
              </div>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="bx bx-calendar-x fs-2 mb-2 d-block"></i>
              No upcoming consultations. Confirmed sessions with farmers show up here.
            </div>
          @endforelse
        </div>
      </div>

      <!-- New Questions to Answer Card -->
      <div class="card mb-4">
        <div class="card-header pb-2 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="bx bx-help-circle text-primary"></i> New Questions to Answer
          </h5>
          <a href="{{ route('expert.qa') }}" class="av-link-more">
            View All <i class="bx bx-chevron-right"></i>
          </a>
        </div>
        <div class="card-body pt-2">
          @forelse($questions as $question)
            <div class="enquiry-row">
              <span class="enquiry-ic"><i class="fa-solid {{ $question->icon() }}"></i></span>
              <div class="flex-grow-1 overflow-hidden">
                <div class="enquiry-title-text">{{ Str::limit($question->title, 90) }}</div>
                <div class="enquiry-sub-text">Asked by {{ $question->user?->name ?: 'a farmer' }} · {{ $question->created_at?->shortRelativeDiffForHumans() }}</div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" data-bs-toggle="modal" data-bs-target="#answerModal"
                data-id="{{ $question->id }}" data-title="{{ $question->title }}" data-body="{{ $question->body }}">
                Answer
              </button>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="bx bx-message-square-dots fs-2 mb-2 d-block"></i>
              No new questions right now. Questions from farmers show up here.
            </div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Right Sidebar Column: Specializations & Recent Article --}}
    <div class="col-lg-4">
      <!-- Specializations Card -->
      <div class="card mb-4">
        <div class="card-header pb-2">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="bx bx-award text-primary"></i> Specializations
          </h5>
        </div>
        <div class="card-body pt-2">
          @if($profile && count($profile->specializationList()))
            <div class="d-flex flex-wrap gap-2 mt-1">
              @foreach($profile->specializationList() as $item)
                <span class="badge-role-expert">{{ $item }}</span>
              @endforeach
            </div>
          @else
            <div class="text-muted mt-1 small">
              No specializations added yet. <a href="{{ route('expert.profile') }}" class="av-link-more">Add them</a>
            </div>
          @endif
        </div>
      </div>

      <!-- Recent Article Card -->
      <div class="card mb-4">
        <div class="card-header pb-2">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="bx bx-news text-primary"></i> Recent Article
          </h5>
        </div>
        <div class="card-body pt-2">
          @if($article)
            <a href="{{ route('blogView', $article->slug ?: 'no-title') }}" target="_blank" class="av-article-card d-block text-decoration-none text-reset">
              <img src="{{ asset($article->image()) }}" alt="{{ $article->name }}" onerror="this.onerror=null;this.src='{{ asset('public/medies/noimage.jpg') }}'">
              <div class="av-article-body">
                <div class="article-title-text">{{ Str::limit($article->name, 80) }}</div>
                <div class="article-meta-text">{{ number_format($article->view) }} {{ $article->view == 1 ? 'view' : 'views' }} · Published {{ $article->created_at?->format('M d, Y') }}</div>
              </div>
            </a>
          @else
            <div class="text-muted small">You have not published an article yet.</div>
          @endif
          <a href="{{ route('expert.articleAction', 'create') }}" class="btn btn-outline-primary w-100 mt-3 d-flex align-items-center justify-content-center gap-1">
            <i class="bx bx-plus"></i> Write New Article
          </a>
        </div>
      </div>

      <!-- Mobile Promo Card -->
      <div class="av-promo d-lg-none">
        <h6>Share Your Expertise</h6>
        <p>Answer farmer questions, publish articles, and grow your reach across the network.</p>
        <a href="{{ route('expert.articleAction', 'create') }}" class="btn-post text-decoration-none">
          <i class="bx bx-plus me-1"></i> Write an Article
        </a>
      </div>
    </div>
  </div>
</div>

@include(expertTheme().'partials.answer-modal')
@include(expertTheme().'partials.reschedule-modal')
@endsection
