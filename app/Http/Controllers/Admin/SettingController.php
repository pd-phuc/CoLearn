<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settingService) {}

    public function index(): View
    {
        $settingGroups = [
            'platform' => [
                'title' => __('admin.tab_platform'),
                'description' => 'Configure platform name, support email, and default currency settings.',
                'settings' => [
                    'name' => ['label' => 'App Name', 'value' => $this->settingService->get('platform', 'name', config('app.name')), 'type' => 'text'],
                    'email' => ['label' => 'Support Email', 'value' => $this->settingService->get('platform', 'email', 'support@colearn.test'), 'type' => 'email'],
                    'currency' => ['label' => 'Currency Symbol', 'value' => $this->settingService->get('platform', 'currency', 'VND'), 'type' => 'text'],
                    'min_topup' => ['label' => 'Min Top-up Amount (VND)', 'value' => $this->settingService->get('platform', 'min_topup', '10000'), 'type' => 'number'],
                ],
            ],
            'sepay' => [
                'title' => __('admin.tab_sepay'),
                'description' => 'Configure VietQR SePay Auto-Bank webhook and bank account info.',
                'settings' => [
                    'bank_id' => ['label' => 'Bank ID / Name', 'value' => $this->settingService->get('sepay', 'bank_id', 'MBBank'), 'type' => 'text'],
                    'account_no' => ['label' => 'Account Number', 'value' => $this->settingService->get('sepay', 'account_no', ''), 'type' => 'text'],
                    'account_name' => ['label' => 'Account Name', 'value' => $this->settingService->get('sepay', 'account_name', ''), 'type' => 'text'],
                    'api_key' => ['label' => 'SePay API Secret Key', 'value' => $this->settingService->get('sepay', 'api_key', ''), 'type' => 'secret'],
                ],
            ],
            'stripe' => [
                'title' => __('admin.tab_stripe'),
                'description' => 'Configure Stripe international credit card payment keys.',
                'settings' => [
                    'publishable' => ['label' => 'Stripe Publishable Key', 'value' => $this->settingService->get('stripe', 'publishable', config('services.stripe.key')), 'type' => 'text'],
                    'secret' => ['label' => 'Stripe Secret Key', 'value' => $this->settingService->get('stripe', 'secret', config('services.stripe.secret')), 'type' => 'secret'],
                    'webhook_secret' => ['label' => 'Stripe Webhook Secret', 'value' => $this->settingService->get('stripe', 'webhook_secret', ''), 'type' => 'secret'],
                ],
            ],
            'email' => [
                'title' => __('admin.tab_email'),
                'description' => 'Configure SMTP / Mailgun email delivery configuration.',
                'settings' => [
                    'host' => ['label' => 'SMTP Host', 'value' => $this->settingService->get('mail', 'host', config('mail.mailers.smtp.host')), 'type' => 'text'],
                    'port' => ['label' => 'SMTP Port', 'value' => $this->settingService->get('mail', 'port', config('mail.mailers.smtp.port')), 'type' => 'text'],
                    'username' => ['label' => 'SMTP Username', 'value' => $this->settingService->get('mail', 'username', config('mail.mailers.smtp.username')), 'type' => 'text'],
                    'password' => ['label' => 'SMTP Password', 'value' => $this->settingService->get('mail', 'password', config('mail.mailers.smtp.password')), 'type' => 'secret'],
                    'from_address' => ['label' => 'From Address', 'value' => $this->settingService->get('mail', 'from_address', config('mail.from.address')), 'type' => 'email'],
                ],
            ],
            'google' => [
                'title' => __('admin.tab_google'),
                'description' => 'Configure Google OAuth Social Login client credentials.',
                'settings' => [
                    'client_id' => ['label' => 'Google Client ID', 'value' => $this->settingService->get('oauth_google', 'client_id', config('services.google.client_id')), 'type' => 'text'],
                    'client_secret' => ['label' => 'Google Client Secret', 'value' => $this->settingService->get('oauth_google', 'client_secret', config('services.google.client_secret')), 'type' => 'secret'],
                ],
            ],
            'facebook' => [
                'title' => __('admin.tab_facebook'),
                'description' => 'Configure Facebook OAuth Social Login client credentials.',
                'settings' => [
                    'client_id' => ['label' => 'Facebook App ID', 'value' => $this->settingService->get('oauth_facebook', 'client_id', config('services.facebook.client_id')), 'type' => 'text'],
                    'client_secret' => ['label' => 'Facebook App Secret', 'value' => $this->settingService->get('oauth_facebook', 'client_secret', config('services.facebook.client_secret')), 'type' => 'secret'],
                ],
            ],
            's3' => [
                'title' => __('admin.tab_s3'),
                'description' => 'Configure Amazon Web Services S3 video and document cloud storage.',
                'settings' => [
                    'aws_key' => ['label' => 'AWS Access Key ID', 'value' => $this->settingService->get('storage', 'aws_key', config('filesystems.disks.s3.key')), 'type' => 'text'],
                    'aws_secret' => ['label' => 'AWS Secret Access Key', 'value' => $this->settingService->get('storage', 'aws_secret', config('filesystems.disks.s3.secret')), 'type' => 'secret'],
                    'aws_region' => ['label' => 'AWS Region', 'value' => $this->settingService->get('storage', 'aws_region', config('filesystems.disks.s3.region')), 'type' => 'text'],
                    'aws_bucket' => ['label' => 'AWS S3 Bucket Name', 'value' => $this->settingService->get('storage', 'aws_bucket', config('filesystems.disks.s3.bucket')), 'type' => 'text'],
                ],
            ],
        ];

        return view('admin.settings.index', compact('settingGroups'));
    }

    public function update(Request $request): RedirectResponse
    {
        $settingsData = $request->input('settings', []);

        foreach ($settingsData as $key => $val) {
            // Map settings key back to group
            $group = match (true) {
                in_array($key, ['name', 'email', 'currency', 'min_topup']) => 'platform',
                in_array($key, ['bank_id', 'account_no', 'account_name', 'api_key']) => 'sepay',
                in_array($key, ['publishable', 'secret', 'webhook_secret']) => 'stripe',
                in_array($key, ['host', 'port', 'username', 'password', 'from_address']) => 'mail',
                in_array($key, ['client_id', 'client_secret']) => 'oauth_google',
                in_array($key, ['aws_key', 'aws_secret', 'aws_region', 'aws_bucket']) => 'storage',
                default => 'platform',
            };

            $isEncrypted = in_array($key, ['api_key', 'secret', 'webhook_secret', 'password', 'client_secret', 'aws_secret']);
            $this->settingService->set($group, $key, $val, $isEncrypted);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', __('admin.settings_saved_success'));
    }
}
