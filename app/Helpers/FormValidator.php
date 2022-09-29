<?php
namespace App\Helpers;

use Twig\Node\Expression\Test\NullTest;

class FormValidator
{

    protected ErrorsHandling $errors;

    public function __construct()
    {
        $this->errors = new ErrorsHandling;
    }

    public function validateEmpty(?string $value): bool
    {
        if (empty($value)) {  
            return false;
        } 

        return true; 
    }

    public function validateString(string $string): bool
    {  
        if (!preg_match ("/^[a-zA-z]*$/", $string) ) {  
            return false; 
        }

        return true;  
    }

    public function validateNumber(int $value): bool
    {  
        if (!preg_match ("/^[0-9]*$/", $value) ){  
            return false;
        } 

        return true;
    }

    public function validateLength(int $min, int $max, string $value): bool
    {   
        if (strlen($value) <= $min || strlen($value) >= $max) {  
            return false;
        } 
        
        return true;   
    }

    public function validateEmail(string $email): bool
    { 
        $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
        if (!preg_match ($pattern, $email) ){  
            return false;
        }

        return true;  
    }

    public function checkSignupForm(array $data): string | null 
    { 
        foreach ($data as $key => $value) {
            // Check email
            if ($key === "email") {
                if ($this->validateEmail($value) === false) {
                    return $this->errors->newError('Cette adresse email est invalide.');
                }
            }
            // Check password
            if ($key === "password") {
                $key = "mot de passe";
                if ($this->validateLength(8, 35, $value) === false) {
                    return $this->errors->newError('Le mot de passe doit contenir 8 caractères minimum et 35 caractères maximum.');
                }
            }
            // Check firstname
            if ($key === "firstname") {
                $key = "prénom";
                if ($this->validateString($value) === false || $this->validateLength(2, 35, $value) === false) {
                    return $this->errors->newError('Vous ne pouvez pas utiliser ce prénom.');
                }
            }
            // Check lastname
            if ($key === "lastname") {
                $key = "nom";
                if ($this->validateString($value) === false || $this->validateLength(2, 35, $value) === false) {
                    return $this->errors->newError('Vous ne pouvez pas utiliser ce nom.');
                }
            }
            // Check empty field
            if($this->validateEmpty($value) === false) {
                return $this->errors->newError("Le champ '$key' ne peut pas être vide.");
            }
        }

        // Return null
        return null;
    }

    public function checkArticleForm(array $data): string | null
    {
        foreach ($data as $key => $value) {
            // Check title
            if ($key === "title") {
                $key = "titre";
                if ($this->validateLength(8, 70, $value) === false) {
                    return $this->errors->newError('Le titre doit contenir 8 caractères minimum et 70 caractères maximum.');
                } 
            } 
            // Check caption
            if ($key === "caption") {
                $key = "description";
                if ($this->validateLength(20, 120, $value) === false) {
                    return $this->errors->newError('La description doit contenir 20 caractères minimum et 120 caractères maximum.');
                } 
            }
            // Check content
            if ($key === "content") {
                $key = "contenu";
                if ($this->validateLength(50, 250, $value) === false) {
                    return $this->errors->newError('Le contenu doit contenir 50 caractères minimum et 250 caractères maximum.');
                } 
            }
            // Check author_id 
            if ($key === "author_id") {
                if ($this->validateNumber($value) === false) {
                    return $this->errors->newError('Un problème est survenue lors du choix de l\'auteur.');
                }
            }
            // Check empty field
            if($this->validateEmpty($value) === false) {
                return $this->errors->newError("Le champ '$key' ne peut pas être vide.");
            }
        }

        // Return null
        return null;
    }

}