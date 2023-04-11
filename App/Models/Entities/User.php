<?php
namespace App\Models\Entities;

use ApplicationLoader;
use App\Services\ValidateService;
class User extends AbstractEntity
{
    public int $id = 0;
    public ?string $account_name = null;
    public ?string $email = null;
    public ?string $full_name = null;
    public ?string $avatar = null;
    public ?string $address = null;
    public ?string $region = null;
    public ?bool $active = null;
    public ?string $account_verified_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct()
    {
        
    }

    public function validate(): array
    {
        $messages = parent::validate();
        $validator = ApplicationLoader::service(ValidateService::class);
        /**
         * @var ValidateService $validator
         */
        if (!$validator->length($this->account_name, 0, 100))
        {
            $messages['account_name'] = 'Account name is not valid';
        }
        if (!$validator->email($this->email))
        {
            $messages['email'] = 'Email is not valid';
        }
        if (!$validator->length($this->full_name, 0, 200))
        {
            $messages['full_name'] = 'Fullname is not valid';
        }
        return $messages;
    }
}
?>