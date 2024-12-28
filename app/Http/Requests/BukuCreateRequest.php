<?php


namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BukuCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
        // return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          
            'nama_buku' => ['required' , 'max:100'],
            'gambar_buku' => ['required' , 'image' , 'mimes:jpeg,png,jpg|max:2048'],
            // 'gambar_qr' => ['required' , 'image' , 'mimes:jpeg,png,jpg|max:2048'],
            'nama_penulis' => ['required' , 'max:100'],
            'nama_penerbit' => ['required' , 'max:100'],
            'jumlah_buku' => ['required' , 'numeric'],
            'buku_tersedia' => ['required' , 'max:100'],
            'tahun_terbit' => ['required' , 'max:100'],
            'created_at' => ['required' ],
        'updated_at' => ['required'  ],
        ];
    }

    protected  function failedValidation(Validator $validator) {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
