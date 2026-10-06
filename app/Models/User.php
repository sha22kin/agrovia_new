<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // HasApiTokens is not used: the API authenticates with users.api_token (APIToken middleware).
    use HasFactory, Notifiable;

    //Models Information Data
    /********
     * 
     * 
     *  ------------------------
     *  Status==0=Inactive, 1=Active, 2=draft
     * ------------------------
     * 
     * Column:
     * 
     * id               =bigint(20):None,
     * permission_id    =int(11):null,
     * name             =varchar(100):null,
     * email            =varchar(100):null,
     * mobile           =varchar(20):null,
     * profile          =text:null,
     * full_address     =text:null,
     * address_line1    =text:null,
     * address_line2    =text:null,
     * postal_address   =varchar(250):null,
     * postal_code      =varchar(20):null,
     * city             =int(4):null,
     * district         =int(4):null,
     * division         =int(4):null,
     * country          =int(4):null,
     * dob              =timestamp:null,
     * gender           =varchar(10):null,
     * status           =tinyint(1):1,
     * fetured          =tinyint(1):0,
     * email_verified_at=tinyint(1):0,
     * password         =varchar(255):none,
     * password_show    =varchar(191):null,
     * remember_token   =varchar(100):null,
     * api_token        =varchar(100):null,
     * device_key       =varchar(255):null,
     * verify_code      =varchar(100):null,
     * verify_code_status=tinyint(1):0,
     * balance          =float(10,2):0.00,
     * subscriber       =tinyint(1):0,
     * customer         =tinyint(1):1,
     * business         =tinyint(1):0,
     * admin            =tinyint(1):0,
     * addedby_id       =bigint(20):0,
     * addedby_at       =timestamp:null,
     * created_at       =timestamp:null
     * updated_at       =timestamp:null
     * 
     * 
     * 
     ****/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_show',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verify_code_expires_at' => 'datetime',
    ];



    public function identities() {
       return $this->hasMany(SocialIdentity::class);
    }

    public function permission(){
        return $this->belongsTo(Permission::class);
    }

    public function addedBy(){
        return $this->belongsTo(User::class,'addedby_id');
    }

    public function comments(){
        return $this->hasMany(Review::class,'addedby_id')->where('type',1);
    }
    
    public function reviews(){
        return $this->hasMany(ProductReview::class);
    }
    
    public function imageFile(){
    	return $this->hasOne(Media::class,'src_id')->where('src_type',6)->where('use_Of_file',1);
    }

    public function image($type=null){
        
        if($this->imageFile){
            if($type=='sm'){
               return $this->imageFile->file_url_sm; 
            }elseif($type=='md'){
               return $this->imageFile->file_url_md;
            }elseif($type=='lg'){
               return $this->imageFile->file_url_lg;
            }else{
               return $this->imageFile->file_url; 
            }
        }else{
            return 'public/medies/profile.png';
        }
    }
    
    public function imageName(){
        
        if($this->imageFile){
            return $this->imageFile->file_rename;
        }else{
            return 'noimage.jpg';
        }
    }


    // Users who did not deactivate their account.
    public function scopeNotDeactivated($query){
        return $query->whereNull('deactivated_at');
    }

    // Url of the profile photo (or the default one) cropped to $size px.
    public function avatar($size=80){
        return $this->imageFile ? route('imageView2',['profile',$this->imageName(),'w'=>$size,'h'=>$size]) : asset($this->image());
    }

    public function bannerFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',6)->where('use_Of_file',2);
    }

    public function banner(){
        
        if($this->bannerFile){
            return $this->bannerFile->file_url;
        }else{
            return 'public/app-assets/images/carousel/22.jpg';
        }
    }
    
    public function bannerName(){
        
        if($this->bannerFile){
            return $this->bannerFile->file_rename;
        }else{
            return 'no-banner.png';
        }
    }
    
    public function countryN(){
        return $this->belongsTo(Country::class,'country');
    }
    
    public function divitionN(){
        return $this->belongsTo(Country::class,'division');
    }

    public function districtN(){
        return $this->belongsTo(Country::class,'district');
    }
    
    
    public function cityN(){
        return $this->belongsTo(Country::class,'city');
    }


    public function user(){
        return $this->belongsTo(User::class,'id');
    }

    public function fullAddress(){

        $addr =$this->address_line1;

        if($this->cityN){
           $addr .=', '.$this->cityN->name;
        }

        if($this->districtN){
           $addr .=', '.$this->districtN->name;
        }

        if($this->postal_code){
           $addr .=' - '.$this->postal_code;
        }

        if($this->divitionN){
           $addr .=', '.$this->divitionN->name;
        }

        return $addr;
        
    }
    
    public function posts(){
        return $this->hasMany(Post::class,'addedby_id')->where('type',1);
    }
    
    public function myListings(){
        return $this->hasMany(Post::class,'addedby_id')->where('type',3);
    }

    /*
     * Dashboard roles (admin / expert / seller / buyer). Nothing extra is stored for them:
     *  - admin  = users.admin flag
     *  - expert = users.expert flag (the admin sets it)
     *  - seller = users.business flag, or a user that already posted an ad (drafts do not count)
     *  - buyer  = any other user with the users.customer flag (an expert is not a buyer)
     * A user lands on the first of admin, expert, seller, buyer.
     */
    protected ?bool $sellerCache = null;

    public function isAdmin(): bool
    {
        return (bool) $this->admin;
    }

    public function isExpert(): bool
    {
        return (bool) $this->expert;
    }

    public function expertProfile(){
        return $this->hasOne(ExpertProfile::class);
    }

    public function isSeller(): bool
    {
        return $this->sellerCache ??= ($this->business || $this->myListings()->where('status','<>','temp')->exists());
    }

    public function isBuyer(): bool
    {
        return $this->customer && !$this->isAdmin() && !$this->isExpert() && !$this->isSeller();
    }

    public function hasDashboardRole(string $role): bool
    {
        return match ($role) {
            'admin' => $this->isAdmin(),
            'expert' => $this->isExpert(),
            'seller' => $this->isSeller(),
            'buyer' => $this->isBuyer(),
            default => (bool) $this->getAttribute($role),
        };
    }

    // "Join as" choices on the register page: key => [title, subtitle].
    public static function registerRoles(): array
    {
        return [
            'seller' => ['Farmer / Seller', 'I grow and sell agricultural products'],
            'buyer' => ['Buyer', 'I want to buy agricultural products'],
            'expert' => ['Expert', 'I provide agricultural services'],
        ];
    }

    // Sets the flags of the role picked at sign-up. customer = buyer, business = seller/farmer:
    // picking "Farmer / Seller" clears the customer flag the users table defaults to, so a seller
    // account is never also counted as a buyer (a buyer can still opt in via becomeSeller()).
    public function assignRegisterRole(?string $role): void
    {
        if ($role === 'seller') {
            $this->customer = 0;
            $this->business = 1;
        } elseif ($role === 'expert') {
            $this->expert = 1;
        }
    }

    // Google / Facebook login buttons (always shown): key => label.
    public static function socialProviders(): array
    {
        return ['google' => 'Google', 'facebook' => 'Facebook'];
    }

    // True once the provider has its id and secret (config/services.php, filled from .env).
    public static function socialConfigured(string $provider): bool
    {
        $filled = fn ($value) => trim((string) $value) !== '' && !str_starts_with(trim((string) $value), '#');

        return array_key_exists($provider, static::socialProviders())
            && $filled(config("services.$provider.client_id"))
            && $filled(config("services.$provider.client_secret"));
    }

    // Finds the account someone typed into a login/identify field - email, mobile (formatting-tolerant), or
    // name - the same way for every caller (LoginRequest's existence check, the password-login flow, and the
    // "Login with OTP" tab all resolve through here, so they can never drift apart).
    public static function findByLoginIdentifier(string $login): ?self
    {
        $login = trim($login);
        // "01712 345-678" typed at login must match the "01712345678" that got saved at registration
        $digitsOnly = preg_replace('/[\s\-()]/', '', $login);

        if (is_numeric($digitsOnly)) {
            $field = 'mobile';
            $login = $digitsOnly;
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'name';
        }

        return static::where($field, $login)->first();
    }

    // Registration email OTP: on when the admin has turned on Mail Status + Register Verify (Mail) and this
    // account has an email to send it to. Off by default until an admin opts in (config/services holds no key
    // that has to be filled for this one — it just needs those two switches and a working SMTP setup).
    public function needsEmailVerification(): bool
    {
        return general()->mail_status && general()->register_verify_mail_user && (bool) $this->email;
    }

    // Generates a fresh 6-digit code, valid for 10 minutes, and saves it.
    public function issueVerifyCode(): string
    {
        $code = (string) random_int(100000, 999999);
        $this->verify_code = $code;
        $this->verify_code_status = 0;
        $this->verify_code_expires_at = now()->addMinutes(10);
        $this->save();

        return $code;
    }

    // True when $code matches and has not expired. On a match the account is marked verified.
    public function confirmVerifyCode(?string $code): bool
    {
        $code = trim((string) $code);
        $valid = $code !== ''
            && hash_equals((string) $this->verify_code, $code)
            && $this->verify_code_expires_at
            && $this->verify_code_expires_at->isFuture();

        if ($valid) {
            $this->verify_code_status = 1;
            $this->email_verified_at = now();
            $this->verify_code = null;
            $this->verify_code_expires_at = null;
            $this->save();
        }

        return $valid;
    }

    // "ra***b@gmail.com" — shown on the verify page so people can tell the code went to the right inbox.
    public function maskedEmail(): string
    {
        if (!$this->email || !str_contains($this->email, '@')) {
            return (string) $this->email;
        }
        [$name, $domain] = explode('@', $this->email, 2);
        $length = mb_strlen($name);
        $masked = $length > 1
            ? mb_substr($name, 0, 1).str_repeat('*', max(1, $length - 2)).mb_substr($name, -1)
            : $name.'*';

        return $masked.'@'.$domain;
    }

    // Text of the dashboard button in the site header.
    public function dashboardLabel(): string
    {
        return match (true) {
            $this->isAdmin() => 'Admin Panel',
            $this->isExpert() => 'Expert Dashboard',
            $this->isSeller() => 'Seller Dashboard',
            default => 'My Dashboard',
        };
    }

    // Route name of the dashboard this user lands on, null when the user has no role at all.
    public function dashboardRoute(): ?string
    {
        if ($this->isAdmin()) {
            return 'admin.dashboard';
        }
        if ($this->isExpert()) {
            return 'expert.dashboard';
        }
        if ($this->isSeller()) {
            return 'seller.dashboard';
        }
        if ($this->isBuyer()) {
            return 'buyer.dashboard';
        }

        return null;
    }



}
