<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    protected $status = true;
    protected $message = 'Thành công';
    protected $code = 200;
    protected $meta = [];

    /**
     * Đặt thông tin phản hồi phụ (metadata)
     */
    public function withMeta(array $meta = [])
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Đặt trạng thái trả về (true/false)
     */
    public function withStatus(bool $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Đặt thông báo trả về
     */
    public function withMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Đặt mã phản hồi HTTP
     */
    public function withCode(int $code): static
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Chuyển dữ liệu thành mảng
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Thêm metadata vào phản hồi JSON
     */
    public function with(Request $request): array
    {
        return [
            'status'  => $this->status ? 'success' : 'error',
            'message' => $this->message,
            'code'    => $this->code,
            'meta'    => $this->meta,
        ];
    }
}
