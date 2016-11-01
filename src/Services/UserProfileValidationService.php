<?php
namespace Javiern\Services;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class UserProfileValidationService
{
    private $validator;

    /**
     * UserProfileValidationService constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    public function validate($data)
    {
        $results = [];

        //validate name
        $results['name'] = $this->validateName(array_key_exists('name', $data) ? $data['name'] : null);

        //validate address
        $results['address'] = $this->validateAddress(array_key_exists('address', $data) ? $data['address'] : null);

        $errors = [];
        foreach ($results as $key => $violations) {
            if(count($violations) !== 0) {
                foreach ($violations as $violation) {
                    $errors[$key][] = $violation->getMessage();
                }
            }
        }

        return $errors;
    }

    public function validateEdit($original, $new)
    {
        $results = [];
        if(
            array_key_exists('id', $original) &&
            array_key_exists('id', $new) &&
            $original['id'] == $new['id']
        ) {
            $results = $this->validate($new);
        } else {
            $results['id'][] = "UserProfile id en content does not match requested";
        }

        return $results;
    }

    protected function validateName($name)
    {
        return  $this->getValidator()->validate(
            $name,
            [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Length([
                    'min' =>   2,
                    'max' => 100
                ])
            ]
        );
    }

    protected function validateAddress($address)
    {
        return $this->getValidator()->validate(
            $address,
            [
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' =>   5,
                    'max' => 4000
                ])
            ]
        );
    }
}