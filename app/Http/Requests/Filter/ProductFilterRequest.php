<?php

namespace App\Http\Requests\Filter;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

    protected function failedValidation(Validator $validator): void
    {
        if ($this->ajax() || $this->wantsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Nieprawidłowe filtry.',
                'errors' => $validator->errors(),
            ], 422));
        }

        $safeParams = $this->except(['date_from', 'date_to']);

        $redirectUrl = url($this->path());
        if (!empty($safeParams)) {
            $redirectUrl .= '?'.http_build_query($safeParams);
        }

        throw (new ValidationException($validator))->redirectTo($redirectUrl);
    }

    /**
     * Komunikaty walidacji po polsku
     */
    public function messages(): array
    {
        return [
            'date_from.after_or_equal' => 'Data "od" nie może być wcześniejsza niż dzisiaj.',
            'date_to.after_or_equal' => 'Data "do" musi być dzisiejsza lub późniejsza i nie może wypadać przed datą "od".',
            'date_from.before_or_equal' => 'Data "od" nie może być późniejsza niż data "do".',
            'date_from.date' => 'Podana data "od" jest nieprawidłowa.',
            'date_to.date' => 'Podana data "do" jest nieprawidłowa.',
            'categories.array' => 'Nieprawidłowy format kategorii.',
            'categories.*.exists' => 'Wybrana kategoria nie istnieje.',
            'sort.in' => 'Nieprawidłowa opcja sortowania.',
            'search.max' => 'Wyszukiwana fraza jest za długa (maksymalnie 255 znaków).',
        ];
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