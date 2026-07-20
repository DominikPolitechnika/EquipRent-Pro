<?php

namespace App\Http\Requests\Filter;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'search' => $this->sanitizeSearch($this->input('search')),
        ]);
    }

    private function sanitizeSearch(?string $value): ?string
    {
        if($value === null){
            return null;
        }

        $value = strip_tags($value);

        $value = trim($value);

        $value = preg_replace('/\s+/', ' ', $value);

        return $value === '' ? null : $value;
    }


    public function rules(): array
    {
        $rules = [
            'search' => ['nullable','string','max:255'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', Rule::exists('equipment_category','id')],
            'price_range' => ['nullable','integer','min:0'],
            'date_from'     => ['nullable', 'date', 'after_or_equal:today'],
            'date_to'       => ['nullable', 'date', 'after_or_equal:today'],
            'sort' => ['nullable','string', Rule::in(['price_asc','price_desc','name_asc','name_desc'])],
        ];

        if($this->filled('date_from') && $this->filled('date_to')){
            $rules['date_from'][] = 'before_or_equal:date_to';
            $rules['date_to'][] = 'after_or_equal:date_from';
        }

        return $rules;
    }
}
