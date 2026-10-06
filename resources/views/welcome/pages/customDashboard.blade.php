<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buyer Profile | Agroviea</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root{
  --av-green-900:#0f4a24;
  --av-green-800:#14602f;
  --av-green-700:#1a7a3c;
  --av-green-600:#228b48;
  --av-green-500:#2e9e57;
  --av-green-100:#e7f6ec;
  --av-green-50:#f3faf5;
  --av-ink:#1c2b22;
  --av-muted:#6b7d72;
  --av-border:#e3ede6;
  --av-gold:#d98a1c;
}

*{box-sizing:border-box;}

body{
  font-family:'Poppins','Hind Siliguri',sans-serif;
  background:#f4f7f5;
  color:var(--av-ink);
  font-size:.92rem;
}

.bn{font-family:'Hind Siliguri','Poppins',sans-serif;}

a{text-decoration:none;}

/* ===== Topbar ===== */
.av-topbar{
  background:#fff;
  border-bottom:1px solid var(--av-border);
  position:sticky;
  top:0;
  z-index:1030;
}
.av-logo-icon{
  width:38px;height:38px;
  background:var(--av-green-700);
  border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:1.05rem;
  flex-shrink:0;
}
.av-logo-text{line-height:1.1;}
.av-logo-text .name{font-weight:800;font-size:1.15rem;color:var(--av-green-800);}
.av-logo-text .tag{font-size:.68rem;color:var(--av-muted);letter-spacing:.3px;}

.av-search-wrap{
  border:1px solid var(--av-border);
  border-radius:10px;
  overflow:hidden;
}
.av-search-wrap input{border:none;box-shadow:none;}
.av-search-wrap input:focus{box-shadow:none;}
.av-search-wrap .form-select{
  border:none;border-left:1px solid var(--av-border);border-radius:0;
  background-color:#fafcfa;font-size:.85rem;max-width:150px;
}
.av-search-wrap .btn-search{
  background:var(--av-green-700);color:#fff;border:none;border-radius:0;
  padding-inline:1.1rem;
}
.av-search-wrap .btn-search:hover{background:var(--av-green-800);color:#fff;}

.av-icon-btn{
  width:40px;height:40px;border-radius:10px;
  background:#f4f7f5;border:1px solid var(--av-border);
  display:flex;align-items:center;justify-content:center;
  color:var(--av-ink);position:relative;
  transition:.15s;
}
.av-icon-btn:hover{background:var(--av-green-50);color:var(--av-green-700);}
.av-badge-dot{
  position:absolute;top:-6px;right:-6px;
  background:var(--av-gold);color:#fff;
  font-size:.62rem;font-weight:700;
  min-width:18px;height:18px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  border:2px solid #fff;
}
.av-user-chip img{width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid var(--av-green-100);}
.av-user-chip .uname{font-size:.83rem;font-weight:600;line-height:1.1;}
.av-user-chip .urole{font-size:.7rem;color:var(--av-green-700);font-weight:600;}

/* ===== Sidebar ===== */
.av-sidebar{
  background:#fff;
  border-right:1px solid var(--av-border);
  min-height:calc(100vh - 65px);
  padding:1rem .75rem;
}
.av-nav-link{
  display:flex;align-items:center;gap:.65rem;
  padding:.6rem .8rem;border-radius:10px;
  color:#4b5d52;font-weight:500;font-size:.87rem;
  margin-bottom:.15rem;
}
.av-nav-link i{width:18px;text-align:center;color:#8a9c90;font-size:.9rem;}
.av-nav-link:hover{background:var(--av-green-50);color:var(--av-green-800);}
.av-nav-link:hover i{color:var(--av-green-700);}
.av-nav-link.active{background:var(--av-green-700);color:#fff;}
.av-nav-link.active i{color:#fff;}
.av-nav-link .pill{
  margin-left:auto;background:var(--av-green-700);color:#fff;
  font-size:.68rem;font-weight:700;padding:.05rem .4rem;border-radius:20px;
}
.av-nav-link.active .pill{background:rgba(255,255,255,.25);}

.av-sub-block{
  background:var(--av-green-50);
  border-radius:12px;
  padding:.5rem;
  margin:.3rem 0 .8rem;
}
.av-sub-link{
  display:block;padding:.42rem .8rem .42rem 2.1rem;
  border-radius:8px;font-size:.82rem;color:#4b5d52;position:relative;
}
.av-sub-link::before{
  content:'';position:absolute;left:1.3rem;top:50%;transform:translateY(-50%);
  width:5px;height:5px;border-radius:50%;background:#b7c6bc;
}
.av-sub-link.active{color:var(--av-green-800);font-weight:700;background:#fff;}
.av-sub-link.active::before{background:var(--av-green-700);}
.av-sub-link:hover{color:var(--av-green-800);}

.av-divider{border-top:1px solid var(--av-border);margin:.7rem 0;}

.av-promo{
  background:linear-gradient(160deg,var(--av-green-800),var(--av-green-600));
  border-radius:16px;color:#fff;padding:1.1rem;margin-top:1rem;overflow:hidden;position:relative;
}
.av-promo h6{font-weight:700;margin-bottom:.4rem;}
.av-promo p{font-size:.78rem;opacity:.9;margin-bottom:.9rem;}
.av-promo .btn-post{
  background:var(--av-gold);border:none;color:#fff;font-weight:600;font-size:.8rem;
  padding:.45rem .9rem;border-radius:8px;
}
.av-promo .fig{
  position:absolute;right:-10px;bottom:-8px;font-size:3.4rem;opacity:.18;
}

/* ===== Main / Banner ===== */
.av-main{padding:1.25rem;}
.av-banner{
  background:linear-gradient(120deg,var(--av-green-800) 0%, var(--av-green-600) 65%, var(--av-green-500) 100%);
  border-radius:18px;
  padding:1.75rem;
  position:relative;
  overflow:hidden;
  color:#fff;
}
.av-banner::after{
  content:'\f6d1';
  font-family:"Font Awesome 6 Free";font-weight:900;
  position:absolute;right:2.5rem;bottom:-1rem;font-size:7rem;
  color:rgba(255,255,255,.08);
}
.av-avatar-wrap{
  width:96px;height:96px;border-radius:50%;
  border:4px solid rgba(255,255,255,.6);
  overflow:hidden;flex-shrink:0;background:#dfeee3;position:relative;
}
.av-avatar-wrap img{width:100%;height:100%;object-fit:cover;}
.av-avatar-cam{
  position:absolute;bottom:0;right:0;
  width:26px;height:26px;border-radius:50%;
  background:var(--av-gold);color:#fff;
  display:flex;align-items:center;justify-content:center;font-size:.7rem;
  border:2px solid #fff;
}
.av-verified-badge{
  background:rgba(255,255,255,.18);
  border:1px solid rgba(255,255,255,.4);
  font-size:.72rem;font-weight:600;
  padding:.2rem .6rem;border-radius:20px;
  display:inline-flex;align-items:center;gap:.3rem;
}
.av-banner .meta-line{font-size:.83rem;opacity:.92;display:flex;align-items:center;gap:.4rem;}
.btn-edit-profile{
  background:#fff;color:var(--av-green-800);font-weight:600;font-size:.85rem;
  border:none;border-radius:9px;padding:.5rem 1rem;
}

/* ===== Stats ===== */
.av-stats-row{
  background:#fff;border-radius:18px;
  margin-top: 20px;position:relative;z-index:2;
  padding:1.1rem .5rem;
  box-shadow:0 6px 24px rgba(20,40,25,.06);
}
.av-stat{display:flex;align-items:center;gap:.7rem;padding:.4rem .9rem;}
.av-stat-icon{
  width:44px;height:44px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;
}
.av-stat .lbl{font-size:.74rem;color:var(--av-muted);font-weight:500;}
.av-stat .val{font-size:1.25rem;font-weight:700;color:var(--av-ink);line-height:1.15;}
.av-stat a{font-size:.72rem;font-weight:600;color:var(--av-green-700);}
.av-stat a i{font-size:.65rem;}
.stat-divider{border-left:1px solid var(--av-border);}

/* ===== Tabs ===== */
.av-tabs{
  background:#fff;border-radius:14px;margin-top:1rem;
  padding:.4rem .6rem;display:flex;gap:.2rem;overflow-x:auto;
  box-shadow:0 2px 10px rgba(20,40,25,.04);
}
.av-tab{
  border:none;background:transparent;padding:.6rem 1rem;
  font-weight:600;font-size:.85rem;color:var(--av-muted);
  border-radius:10px;white-space:nowrap;
}
.av-tab.active{color:var(--av-green-800);background:var(--av-green-50);}
.av-tab-underline{height:3px;width:26px;background:var(--av-green-700);border-radius:3px;margin:2px auto 0;}

/* ===== Cards ===== */
.av-card{
  background:#fff;border-radius:16px;padding:1.25rem;
  box-shadow:0 2px 10px rgba(20,40,25,.04);
  margin-top:1rem;
}
.av-card-title{
  font-weight:700;font-size:.95rem;display:flex;align-items:center;gap:.55rem;
}
.av-card-title i{color:var(--av-green-700);}
.av-link-more{font-size:.78rem;font-weight:600;color:var(--av-green-700);}

.av-info-strip{
  background:var(--av-green-50);border-radius:12px;
  padding:.8rem 1rem;margin-top:1rem;
  display:flex;flex-wrap:wrap;gap:1rem 1.6rem;font-size:.83rem;
}
.av-info-strip span i{color:var(--av-green-700);margin-right:.4rem;}

.order-row{
  display:flex;align-items:center;gap:.85rem;
  padding:.7rem 0;border-bottom:1px solid var(--av-border);
}
.order-row:last-child{border-bottom:none;}
.order-thumb{
  width:52px;height:52px;border-radius:10px;object-fit:cover;flex-shrink:0;background:#eee;
}
.order-title{font-weight:600;font-size:.87rem;}
.order-seller{font-size:.75rem;color:var(--av-muted);}
.order-qty{font-size:.8rem;color:var(--av-ink);min-width:70px;}
.order-price{font-weight:700;font-size:.85rem;min-width:80px;}
.badge-status{font-size:.7rem;font-weight:600;padding:.25rem .6rem;border-radius:20px;}
.badge-delivered{background:#e6f4ea;color:var(--av-green-700);}
.badge-completed{background:#eaf1fb;color:#2563a8;}
.order-date{font-size:.72rem;color:var(--av-muted);}

.enquiry-row{
  display:flex;align-items:center;gap:.85rem;padding:.75rem;
  border:1px solid var(--av-border);border-radius:12px;
}
.enquiry-ic{
  width:40px;height:40px;border-radius:10px;background:var(--av-green-50);
  display:flex;align-items:center;justify-content:center;color:var(--av-green-700);flex-shrink:0;
}

/* Right column mini-cards */
.av-mini-card{
  background:#fff;border-radius:16px;padding:1.1rem;
  box-shadow:0 2px 10px rgba(20,40,25,.04);
  margin-top:1rem;
}
.verify-item{
  display:flex;align-items:center;gap:.55rem;padding:.4rem 0;font-size:.84rem;
}
.verify-item i{color:var(--av-green-600);}
.btn-verify-details{
  background:var(--av-green-700);color:#fff;border:none;border-radius:9px;
  padding:.55rem 1rem;font-weight:600;font-size:.83rem;width:100%;margin-top:.5rem;
}
.btn-verify-details:hover{background:var(--av-green-800);color:#fff;}

.badge-soft-green{
  background:var(--av-green-100);color:var(--av-green-800);
  font-size:.75rem;font-weight:600;padding:.3rem .7rem;border-radius:8px;
}
.area-tag{font-size:.83rem;}

.pref-item{display:flex;gap:.6rem;padding:.5rem 0;}
.pref-ic{
  width:32px;height:32px;border-radius:8px;background:var(--av-green-50);
  display:flex;align-items:center;justify-content:center;color:var(--av-green-700);
  flex-shrink:0;font-size:.8rem;
}
.pref-label{font-size:.78rem;font-weight:600;color:var(--av-ink);}
.pref-value{font-size:.8rem;color:var(--av-muted);}

/* Mobile offcanvas tweaks */
@media (max-width: 991.98px){
  .av-sidebar{border-right:none;min-height:auto;}
}
@media (max-width: 575.98px){
  .av-banner{padding:1.25rem;}
  .av-avatar-wrap{width:76px;height:76px;}
  .av-stat{padding:.5rem .5rem;}
}
</style>
</head>
<body>

<!-- ============ TOPBAR ============ -->
<header class="av-topbar py-2">
  <div class="container-fluid px-3">
    <div class="row align-items-center gy-2">
      <div class="col-6 col-lg-2 d-flex align-items-center gap-2">
        <button class="btn btn-light border d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#avSidebar">
          <i class="fa-solid fa-bars"></i>
        </button>
        <span class="av-logo-icon"><i class="fa-solid fa-seedling"></i></span>
        <div class="av-logo-text d-none d-sm-block">
          <div class="name">Agroviea</div>
          <div class="tag">Empowering Farmers</div>
        </div>
      </div>

      <div class="col-12 col-lg-6 order-3 order-lg-2">
        <div class="av-search-wrap d-flex align-items-center">
          <input type="text" class="form-control" placeholder="Search crops, seeds, fertilizers, machinery...">
          <select class="form-select d-none d-md-block">
            <option>All Categories</option>
            <option>Crops</option>
            <option>Seeds</option>
            <option>Fertilizers</option>
            <option>Machinery</option>
          </select>
          <button class="btn btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
      </div>

      <div class="col-6 col-lg-4 order-2 order-lg-3">
        <div class="d-flex align-items-center justify-content-end gap-2">
          <a href="#" class="av-icon-btn d-none d-sm-flex">
            <i class="fa-regular fa-comment-dots"></i>
            <span class="av-badge-dot">3</span>
          </a>
          <a href="#" class="av-icon-btn">
            <i class="fa-regular fa-bell"></i>
            <span class="av-badge-dot">8</span>
          </a>
          <div class="dropdown">
            <a href="#" class="av-user-chip d-flex align-items-center gap-2" data-bs-toggle="dropdown">
              <img src="https://i.pravatar.cc/80?img=13" alt="Raihan Ahmed">
              <span class="d-none d-md-block">
                <span class="uname d-block">Raihan Ahmed</span>
                <span class="urole d-block">Buyer</span>
              </span>
              <i class="fa-solid fa-chevron-down d-none d-md-block small text-muted"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
              <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">

    <!-- ============ SIDEBAR (desktop) ============ -->
    <aside class="col-lg-3 col-xl-2 d-none d-lg-block px-0">
      <nav class="av-sidebar">
        <a href="#" class="av-nav-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="#" class="av-nav-link"><i class="fa-solid fa-store"></i> Marketplace</a>
        <a href="#" class="av-nav-link"><i class="fa-regular fa-file-lines"></i> My Orders</a>
        <a href="#" class="av-nav-link"><i class="fa-regular fa-comment"></i> My Enquiries</a>
        <a href="#" class="av-nav-link"><i class="fa-regular fa-heart"></i> My Wishlist</a>
        <a href="#" class="av-nav-link"><i class="fa-regular fa-rectangle-list"></i> My Ads</a>
        <a href="#" class="av-nav-link">
          <i class="fa-regular fa-message"></i> Messages
          <span class="pill">12</span>
        </a>
        <a href="#" class="av-nav-link"><i class="fa-solid fa-users"></i> Community</a>

        <a href="#" class="av-nav-link active mt-2"><i class="fa-regular fa-circle-user"></i> My Profile</a>
        <div class="av-sub-block">
          <a href="#" class="av-sub-link active">Profile Overview</a>
          <a href="#" class="av-sub-link">Business Info</a>
          <a href="#" class="av-sub-link">Addresses</a>
          <a href="#" class="av-sub-link">Payment Methods</a>
          <a href="#" class="av-sub-link">Settings</a>
        </div>

        <a href="#" class="av-nav-link"><i class="fa-solid fa-magnifying-glass"></i> Saved Searches</a>
        <div class="av-divider"></div>
        <a href="#" class="av-nav-link"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>

        <div class="av-promo">
          <i class="fa-solid fa-tractor fig"></i>
          <h6>Grow Your Business<br>With Agroviea</h6>
          <p>Post your buying requirement and get best sellers.</p>
          <button class="btn btn-post"><i class="fa-solid fa-bullhorn me-1"></i> Post Requirement</button>
        </div>
      </nav>
    </aside>

    <!-- ============ SIDEBAR (offcanvas mobile) ============ -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="avSidebar">
      <div class="offcanvas-header border-bottom">
        <span class="av-logo-text">
          <span class="name d-block">Agroviea</span>
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body p-2">
        <nav class="av-sidebar" style="min-height:auto;">
          <a href="#" class="av-nav-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
          <a href="#" class="av-nav-link"><i class="fa-solid fa-store"></i> Marketplace</a>
          <a href="#" class="av-nav-link"><i class="fa-regular fa-file-lines"></i> My Orders</a>
          <a href="#" class="av-nav-link"><i class="fa-regular fa-comment"></i> My Enquiries</a>
          <a href="#" class="av-nav-link"><i class="fa-regular fa-heart"></i> My Wishlist</a>
          <a href="#" class="av-nav-link"><i class="fa-regular fa-rectangle-list"></i> My Ads</a>
          <a href="#" class="av-nav-link"><i class="fa-regular fa-message"></i> Messages <span class="pill">12</span></a>
          <a href="#" class="av-nav-link"><i class="fa-solid fa-users"></i> Community</a>
          <a href="#" class="av-nav-link active mt-2"><i class="fa-regular fa-circle-user"></i> My Profile</a>
          <div class="av-sub-block">
            <a href="#" class="av-sub-link active">Profile Overview</a>
            <a href="#" class="av-sub-link">Business Info</a>
            <a href="#" class="av-sub-link">Addresses</a>
            <a href="#" class="av-sub-link">Payment Methods</a>
            <a href="#" class="av-sub-link">Settings</a>
          </div>
          <a href="#" class="av-nav-link"><i class="fa-solid fa-magnifying-glass"></i> Saved Searches</a>
          <div class="av-divider"></div>
          <a href="#" class="av-nav-link"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </nav>
      </div>
    </div>

    <!-- ============ MAIN ============ -->
    <main class="col-lg-9 col-xl-10 av-main">

      <!-- Banner -->
      <div class="av-banner">
        <div class="row align-items-center g-3">
          <div class="col-12 col-md-8">
            <div class="d-flex align-items-center gap-3 flex-wrap">
              <div class="av-avatar-wrap">
                <img src="https://i.pravatar.cc/200?img=13" alt="Raihan Ahmed">
                <span class="av-avatar-cam"><i class="fa-solid fa-camera"></i></span>
              </div>
              <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  <h4 class="mb-0 fw-bold">Raihan Ahmed</h4>
                  <span class="av-verified-badge"><i class="fa-solid fa-circle-check"></i> Verified Buyer</span>
                </div>
                <div class="meta-line mt-2"><i class="fa-regular fa-id-card"></i> Buyer ID: BUY-24568 <i class="fa-regular fa-copy ms-1" style="cursor:pointer;"></i></div>
                <div class="meta-line mt-1"><i class="fa-solid fa-location-dot"></i> Bogura, Bangladesh</div>
                <div class="meta-line mt-1"><i class="fa-regular fa-calendar"></i> Member since May 2024</div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 text-md-end">
            <button class="btn btn-edit-profile"><i class="fa-solid fa-pen me-2"></i>Edit Profile</button>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="av-stats-row">
        <div class="row g-3 g-lg-0 text-center text-sm-start">
          <div class="col-6 col-lg">
            <div class="av-stat">
              <span class="av-stat-icon" style="background:var(--av-green-100);color:var(--av-green-700);"><i class="fa-solid fa-bag-shopping"></i></span>
              <div>
                <div class="lbl">Total Orders</div>
                <div class="val">28</div>
                <a href="#">View Orders <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg stat-divider">
            <div class="av-stat">
              <span class="av-stat-icon" style="background:#e7f6ec;color:var(--av-green-600);"><i class="fa-solid fa-check"></i></span>
              <div>
                <div class="lbl">Completed Orders</div>
                <div class="val">19</div>
                <a href="#">View Completed <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg stat-divider">
            <div class="av-stat">
              <span class="av-stat-icon" style="background:#e7f6ec;color:var(--av-green-600);"><i class="fa-solid fa-sack-dollar"></i></span>
              <div>
                <div class="lbl">Total Spent</div>
                <div class="val">৳ 2,45,600</div>
                <a href="#">View Transactions <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg stat-divider">
            <div class="av-stat">
              <span class="av-stat-icon" style="background:#eaf1fb;color:#2563a8;"><i class="fa-solid fa-truck-fast"></i></span>
              <div>
                <div class="lbl">Enquiries Sent</div>
                <div class="val">36</div>
                <a href="#">View Enquiries <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg stat-divider">
            <div class="av-stat">
              <span class="av-stat-icon" style="background:#fdeceb;color:#e0574c;"><i class="fa-solid fa-heart"></i></span>
              <div>
                <div class="lbl">Saved Items</div>
                <div class="val">47</div>
                <a href="#">View Wishlist <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="av-tabs">
        <div class="text-center">
          <button class="av-tab active">Overview</button>
          <div class="av-tab-underline"></div>
        </div>
        <button class="av-tab">Business Information</button>
        <button class="av-tab">Buying Preferences</button>
        <button class="av-tab">Activity</button>
        <button class="av-tab">Reviews</button>
      </div>

      <!-- Content -->
      <div class="row">
        <div class="col-lg-8">

          <!-- About -->
          <div class="av-card">
            <div class="av-card-title"><i class="fa-regular fa-address-card"></i> About Me</div>
            <p class="mb-0 mt-2 text-secondary" style="font-size:.87rem;">
              I am a wholesale buyer and retailer of agricultural products. I buy paddy, maize, vegetables,
              potatoes and pulses in large quantity for distribution across Bogura and nearby districts.
            </p>
            <div class="av-info-strip">
              <span><i class="fa-solid fa-phone"></i>+880 1712 345678</span>
              <span><i class="fa-regular fa-envelope"></i>raihan.ahmed@email.com</span>
              <span><i class="fa-solid fa-user-plus"></i>Joined: May 20, 2024</span>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="av-card">
            <div class="d-flex justify-content-between align-items-center">
              <div class="av-card-title"><i class="fa-regular fa-file-lines"></i> Recent Orders</div>
              <a href="#" class="av-link-more">View All Orders <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="order-row">
              <img class="order-thumb" src="https://images.unsplash.com/photo-1568347355280-d33fdf8a4be1?q=80&w=100&auto=format&fit=crop" alt="Paddy">
              <div class="flex-grow-1">
                <div class="order-title">Paddy (ধান)</div>
                <div class="order-seller">Seller: Mahin Agro Farm</div>
              </div>
              <div class="order-qty">5000 kg</div>
              <div class="order-price">৳ 75,000</div>
              <div class="text-end">
                <span class="badge-status badge-delivered">Delivered</span>
                <div class="order-date">May 28, 2024</div>
              </div>
            </div>

            <div class="order-row">
              <img class="order-thumb" src="https://images.unsplash.com/photo-1518977676601-b53f82aba655?q=80&w=100&auto=format&fit=crop" alt="Potato">
              <div class="flex-grow-1">
                <div class="order-title">Potato (আলু)</div>
                <div class="order-seller">Seller: Fresh Farmers</div>
              </div>
              <div class="order-qty">2000 kg</div>
              <div class="order-price">৳ 32,000</div>
              <div class="text-end">
                <span class="badge-status badge-completed">Completed</span>
                <div class="order-date">May 18, 2024</div>
              </div>
            </div>

            <div class="order-row">
              <img class="order-thumb" src="https://images.unsplash.com/photo-1615486511262-bea99367d081?q=80&w=100&auto=format&fit=crop" alt="Onion">
              <div class="flex-grow-1">
                <div class="order-title">Onion (পেঁয়াজ)</div>
                <div class="order-seller">Seller: Green Field</div>
              </div>
              <div class="order-qty">1500 kg</div>
              <div class="order-price">৳ 30,000</div>
              <div class="text-end">
                <span class="badge-status badge-delivered">Delivered</span>
                <div class="order-date">May 10, 2024</div>
              </div>
            </div>

            <div class="order-row">
              <img class="order-thumb" src="https://images.unsplash.com/photo-1601593768799-76d7f14e2f7c?q=80&w=100&auto=format&fit=crop" alt="Maize">
              <div class="flex-grow-1">
                <div class="order-title">Maize (ভুট্টা)</div>
                <div class="order-seller">Seller: Agro Supply BD</div>
              </div>
              <div class="order-qty">3000 kg</div>
              <div class="order-price">৳ 54,000</div>
              <div class="text-end">
                <span class="badge-status badge-completed">Completed</span>
                <div class="order-date">Apr 28, 2024</div>
              </div>
            </div>
          </div>

          <!-- Recent Enquiries -->
          <div class="av-card">
            <div class="d-flex justify-content-between align-items-center">
              <div class="av-card-title"><i class="fa-regular fa-comment-dots"></i> Recent Enquiries</div>
              <a href="#" class="av-link-more">View All Enquiries <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="mt-3 enquiry-row">
              <span class="enquiry-ic"><i class="fa-solid fa-list-check"></i></span>
              <div class="flex-grow-1">
                <div class="fw-semibold" style="font-size:.87rem;">Looking for Quality Paddy</div>
                <div class="text-muted" style="font-size:.78rem;">Quantity: 8000 kg</div>
              </div>
              <div class="text-end">
                <div class="fw-semibold text-success" style="font-size:.8rem;">5 Quotes Received</div>
                <div class="order-date">May 30, 2024</div>
              </div>
              <i class="fa-solid fa-chevron-right text-muted"></i>
            </div>
          </div>

        </div>

        <!-- Right column -->
        <div class="col-lg-4">

          <div class="av-mini-card">
            <div class="av-card-title"><i class="fa-solid fa-shield-halved"></i> Verification Status</div>
            <div class="verify-item"><i class="fa-solid fa-circle-check"></i> Email Verified</div>
            <div class="verify-item"><i class="fa-solid fa-circle-check"></i> Phone Verified</div>
            <div class="verify-item"><i class="fa-solid fa-circle-check"></i> NID Verified</div>
            <div class="verify-item"><i class="fa-solid fa-circle-check"></i> Business Verified</div>
            <button class="btn-verify-details">View Verification Details</button>
          </div>

          <div class="av-mini-card">
            <div class="av-card-title"><i class="fa-solid fa-people-group"></i> Business Type</div>
            <div class="mt-2"><span class="badge-soft-green">Wholesaler</span></div>
            <div class="av-card-title mt-3"><i class="fa-solid fa-map-location-dot"></i> Areas of Operation</div>
            <div class="area-tag text-secondary mt-1">Bogura, Sirajganj, Natore, Rajshahi, Joypurhat</div>
          </div>

          <div class="av-mini-card">
            <div class="d-flex justify-content-between align-items-center">
              <div class="av-card-title"><i class="fa-solid fa-sliders"></i> Buying Preferences</div>
              <a href="#" class="av-link-more">Edit</a>
            </div>

            <div class="pref-item">
              <span class="pref-ic"><i class="fa-solid fa-wheat-awn"></i></span>
              <div>
                <div class="pref-label">Interested In</div>
                <div class="pref-value">Paddy, Maize, Potato, Onion, Lentils, Vegetables</div>
              </div>
            </div>
            <div class="pref-item">
              <span class="pref-ic"><i class="fa-solid fa-award"></i></span>
              <div>
                <div class="pref-label">Preferred Quality</div>
                <div class="pref-value">Premium Quality</div>
              </div>
            </div>
            <div class="pref-item">
              <span class="pref-ic"><i class="fa-solid fa-box"></i></span>
              <div>
                <div class="pref-label">Preferred Quantity</div>
                <div class="pref-value">Bulk (1000+ kg)</div>
              </div>
            </div>
            <div class="pref-item">
              <span class="pref-ic"><i class="fa-solid fa-coins"></i></span>
              <div>
                <div class="pref-label">Budget Range</div>
                <div class="pref-value">৳ 10,000 - ৳ 5,00,000</div>
              </div>
            </div>
            <div class="pref-item">
              <span class="pref-ic"><i class="fa-solid fa-truck"></i></span>
              <div>
                <div class="pref-label">Preferred Delivery</div>
                <div class="pref-value">Within Bangladesh</div>
              </div>
            </div>
          </div>

          <!-- Promo card visible on mobile below content -->
          <div class="av-promo d-lg-none">
            <i class="fa-solid fa-tractor fig"></i>
            <h6>Grow Your Business<br>With Agroviea</h6>
            <p>Post your buying requirement and get best sellers.</p>
            <button class="btn btn-post"><i class="fa-solid fa-bullhorn me-1"></i> Post Requirement</button>
          </div>

        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.av-tab').forEach(tab=>{
  tab.addEventListener('click',()=>{
    document.querySelectorAll('.av-tab').forEach(t=>{t.classList.remove('active'); t.parentElement.querySelector?.('.av-tab-underline')?.remove();});
    tab.classList.add('active');
  });
});
</script>
</body>
</html>
