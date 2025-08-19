<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRegisterRequest;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Laravel\Fortify\Fortify::loginView(fn () => view('auth.login'));
        \Laravel\Fortify\Fortify::registerView(fn () => view('auth.register'));

        // ログイン/登録完了後は常にトップへ
        Fortify::redirects('login', '/');
        Fortify::redirects('register', '/');
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // 認証時の独自バリデーションとエラーメッセージ
        Fortify::authenticateUsing(function (Request $request) {
            $validator = Validator::make(
                $request->all(),
                LoginRegisterRequest::loginRules(),
                LoginRegisterRequest::messageMap()
            );
            if ($validator->fails()) {
                throw (new ValidationException($validator))->errorBag('login');
            }

            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => 'このメールアドレスのユーザーが見つかりません',
                ])->errorBag('login');
            }

            if (!Hash::check($request->input('password'), $user->password)) {
                throw ValidationException::withMessages([
                    'password' => 'パスワードが正しくありません',
                ])->errorBag('login');
            }

            return $user; // 認証成功
        });
    }
}
