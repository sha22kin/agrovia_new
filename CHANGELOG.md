# Agroviea — কাজের তালিকা (Changelog)

অক্টোবর ২০২৬-এ প্রজেক্টে যা যা কাজ হয়েছে, বিভাগ অনুযায়ী সাজানো। প্রতিটা কাজের পাশে কমিট নম্বর দেওয়া আছে।

---

## ১. সার্ভার, ভার্সন ও স্পিড

### Laravel ও PHP ভার্সন
- Laravel **10 → 12.70.1** (Laravel 12-এর সর্বশেষ) — `3c86618`
  - প্রথমে Laravel 13-এ নেওয়া হয়েছিল (`7578d7c`)। পরে cPanel সার্ভারের Composer PHP 8.2.35-এ চলে বলে Laravel 12-এ নামানো হয়। Laravel 12 PHP 8.2 থেকে 8.4 পর্যন্ত সব ভার্সনে চলে।
- সাইট চলবে **PHP 8.4.27**-এ, Composer চলবে **PHP 8.2.35**-এ — `6dfaa42`, `3c86618`
- প্যাকেজ আপডেট: sanctum 4, dompdf 3.1, collision 8, phpunit 11; `spatie/laravel-ignition` বাদ দেওয়া হয়েছে
- আপগ্রেডের সময় যা ঠিক করা হয়েছে:
  - হোম পেজের SEO কোডে `@context` লেখাটা নতুন Laravel-এ Blade কমান্ড হয়ে গিয়েছিল, তাই পেজ ভেঙে যাচ্ছিল — ঠিক করা হয়েছে
  - ছবি আপলোড/রিসাইজ (Intervention Image) নতুন Laravel-এর সাথে কাজ করছিল না — ঠিক করা হয়েছে
  - API আর ওয়েবের কয়েকটা route-এর নাম একই ছিল, তাই `php artisan optimize` fail করত — API route-এর নামের আগে `api.` যোগ করা হয়েছে (URL একই আছে)
  - PHP 8.5-এর একটা deprecation warning (`config/database.php`) ঠিক করা হয়েছে
- `bootstrap/cache` ফোল্ডার git থেকে হারিয়ে যাচ্ছিল, তাই `git pull` করলে সাইট বন্ধ হয়ে যেত — ঠিক করা হয়েছে — `b5feb62`

### স্পিড অপটিমাইজেশন — `463d465`, `7578d7c`, `342d7eb`
- প্রতিটা পেজে সেটিংসের জন্য database-এ ২০০-র বেশি query যাচ্ছিল। এখন প্রতি request-এ মাত্র একবার যায় (`general()` helper)
- বড় ছবিগুলো কমপ্রেস করা হয়েছে: **8.4MB থেকে 0.6MB** (লোগো, favicon, Google Play ব্যানার ইত্যাদি)
- `.htaccess`-এ gzip compression আর ব্রাউজার ক্যাশিং চালু করা হয়েছে
- হোম পেজ আগে একসাথে সব product লোড করত, এখন শুধু ৮টা

---

## ২. Login ও Register পেজ
- Login পেজে নতুন ছবি: Agroviea লোগোসহ কৃষক — `b5feb62`
- Register পেজে নতুন ছবি: ধান রোপণরত নারী কৃষক — `570a21d`
- জমি ও ফসলের তথ্য: business profile-এ নতুন field — `243786d`

---

## ৩. হোম পেজ
- **Featured products:** সর্বশেষ ৮টা product আর নিচে "See more products" বাটন। বাটন আর "View all" দুটোই Marketplace পেজে নিয়ে যায় — `342d7eb`
- **Direct from farmers:** নতুন প্রোফাইল-কার্ড ডিজাইন: সবুজ কভার, emoji-র বদলে avatar, verified লেবেল, product চিপ — `eae988b`, `ad27405`
- **Open buyer requirements:** Featured products-এর মতো ছবিসহ কার্ড, এক সারিতে ৪টা। "All RFQs" আর "Submit quote" RFQ পেজে নিয়ে যায় — `4d0847d`, `c5a95db`

---

## ৪. RFQ পেজ
- চাহিদাগুলো হোম পেজের মতো ছবিসহ কার্ডে দেখায় — `6f15077`
  - ছবি আসে চাহিদার category থেকে। Admin category-তে ছবি না দিলে category অনুযায়ী একটা ঠিক করা ছবি দেখায় (গরু, ট্রাক্টর, আলু, চারা, মাটি, ভুট্টা, ধানক্ষেত)
- "Post your requirement" ফর্ম সরানো হয়েছে। কার্ড এখন পুরো চওড়ায়, এক সারিতে ৪টা, প্রতি পেজে ১২টা — `2daae91`
- নতুন ছবিগুলো (`public/welcome/images/rfq/`) Pexels থেকে নেওয়া, ক্রেডিট ছাড়াই ব্যবহার করা যায়

---

## ৫. মেসেজ / চ্যাট (buyer, seller, expert)

### Product নিয়ে মেসেজ — `6ba1b81`
- Product পেজে "Chat with seller" চাপলে চ্যাটে ঐ product-এর ছবি, নাম আর দাম দেখায় ("Replying about this ad")
- পাঠানো মেসেজের ওপরে product কার্ড থাকে, buyer আর seller দুজনের চ্যাটেই
- শুধু ঐ seller-এর নিজের live product জোড়া যায়

### লাইভ চ্যাট — `bfab268`
- পেজ refresh ছাড়াই নতুন মেসেজ আসে (প্রতি ৪ সেকেন্ডে চেক করে, ট্যাব খোলা থাকলে)
- মেসেজ পাঠালে পেজ reload হয় না
- চ্যাটের তালিকা, শেষ মেসেজ আর না-পড়া মেসেজের সংখ্যাও নিজে থেকে আপডেট হয়

### Reply, Like, Pin, Delete ও Seen — `9bc6ca3`
- **Reply:** নির্দিষ্ট কোনো মেসেজের উত্তর দেওয়া যায়, মূল মেসেজটা quote হয়ে থাকে
- **Like:** মেসেজে ❤
- **Pin:** pin করা মেসেজ চ্যাটের ওপরে দেখায়
- **Delete for me:** যেকোনো সময়, শুধু নিজের দিক থেকে মোছে
- **Delete for everyone:** seen না হওয়া পর্যন্ত, আর seen হওয়ার পর **২ মিনিট** পর্যন্ত। এরপর শুধু "Delete for me" করা যায় (সার্ভারেও যাচাই হয়)
- **Status:** নিজের মেসেজে "Sent ✓" বা "Seen ✓✓"

### নতুন ডিজাইন — `df45828`
- পুরো উচ্চতার চ্যাট কার্ড, সার্চসহ "Chats" তালিকা, Today/Yesterday লেবেল, সাদা/সবুজ বাবল, pinned পট্টি, গোল send বাটন
- মোবাইলে একবারে একটা অংশ দেখায় (তালিকা অথবা চ্যাট), সাথে back বাটন। মেসেজে tap করলে বাটনগুলো আসে
- চ্যাটের সব স্টাইল এক ফাইলে: `public/dashboard/css/chat.css`

---

## সার্ভারে আপলোডের পর যা করতে হবে

1. cPanel → **MultiPHP Manager**-এ সাইটের PHP **8.4** সিলেক্ট করুন
2. সার্ভারের `.env`-এ রাখুন: `APP_ENV=production`, `APP_DEBUG=false`
3. cPanel Terminal-এ চালান:
   ```
   composer install --no-dev --optimize-autoloader
   php artisan migrate
   php artisan optimize
   ```
   `php artisan migrate` এই নতুন টেবিল আর কলামগুলো যোগ করবে:
   - `business_profiles`: জমি আর ফসল
   - `messages`: `post_id`, `reply_to_id`, `pinned_at`, `deleted_at`, `sender_hidden_at`, `receiver_hidden_at`
   - নতুন টেবিল `message_likes`

   **এটা না চালালে চ্যাট পেজ খুলবে না।**
4. `public/admin.zip` (20MB) যে কেউ ডাউনলোড করতে পারে। দরকার না থাকলে সার্ভার থেকে মুছে দিন

## এখনো বাকি আছে
- হোম পেজের "Direct from farmers" আর "Open buyer requirements" অংশে এখন নমুনা তথ্য দেখায়। seller-রা প্রোফাইল পূরণ করলে আর আসল চাহিদা বাড়লে সেগুলো database থেকে দেখানো যাবে
- "View profile" বাটনের জন্য seller-দের পাবলিক প্রোফাইল পেজ এখনো নেই
- Database-এ কিছু টেস্ট ডাটা আছে (যেমন RFQ: "xcxcxc", "5 kg chal")। লাইভে যাওয়ার আগে admin panel থেকে মুছে দিন
