<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class PhoneNumber implements ValidationRule
{
    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if value is null or empty
        if (empty($value)) {
            return;
        }

        // Validate phone number format (Vietnamese format)
        if (!preg_match('/^(0|\+84)[0-9]{9,10}$/', $value)) {
            $fail('Số điện thoại không đúng định dạng. Vui lòng nhập số điện thoại hợp lệ.');
            return;
        }

        // Check uniqueness
        $query = \App\Models\User::where('phone', $value);
        
        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail('Số điện thoại đã được sử dụng bởi tài khoản khác.');
        }
    }
}
