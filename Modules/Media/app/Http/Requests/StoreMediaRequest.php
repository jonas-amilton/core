<?php

declare(strict_types=1);

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class StoreMediaRequest extends FormRequest
{
    public const MAX_KILOBYTES = 10240;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * The smaller of our own limit and the PHP limits, in kilobytes.
     */
    public static function maxKilobytes(): int
    {
        return intdiv(min((int) UploadedFile::getMaxFilesize(), self::MAX_KILOBYTES * 1024), 1024);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:'.self::MAX_KILOBYTES,
                'mimes:jpg,jpeg,png,webp,gif,pdf',
            ],
            'alt' => ['nullable', 'string', 'max:200'],
            'caption' => ['nullable', 'string', 'max:200'],
        ];
    }

    /**
     * A file rejected by php.ini arrives here as "required", which is misleading.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $uploadLimit = (string) ini_get('upload_max_filesize');
        $postLimit = (string) ini_get('post_max_size');

        return [
            'file.required' => "Nenhum arquivo chegou ao servidor. Se você selecionou um, ele passa do limite do PHP ({$uploadLimit} por arquivo, {$postLimit} por requisição).",
            'file.file' => 'O envio do arquivo falhou. Verifique o arquivo e tente novamente.',
            'file.max' => 'O arquivo passa do limite de '.intdiv(self::MAX_KILOBYTES, 1024).' MB.',
            'file.mimes' => 'Formato não aceito. Envie JPG, PNG, WebP, GIF ou PDF.',
        ];
    }
}
