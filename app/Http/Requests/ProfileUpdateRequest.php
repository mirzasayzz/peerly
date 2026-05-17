<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9_]+$/', 
                Rule::unique(User::class)->ignore($this->user()->id),
                function ($attribute, $value, $fail) {
                    $user = $this->user();
                    if ($user->username !== $value) {
                        if ($user->username_changed_at && $user->username_changed_at->copy()->addDays(90)->isFuture()) {
                            $daysLeft = now()->diffInDays($user->username_changed_at->copy()->addDays(90)) + 1;
                            $fail("You can only change your username once every 90 days. Please wait {$daysLeft} more days.");
                        }
                    }
                }
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'university' => ['nullable', 'string', 'max:255'],
            'major' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:20'],
            'github' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:3072'], // 3MB limit
        ];
    }
}
