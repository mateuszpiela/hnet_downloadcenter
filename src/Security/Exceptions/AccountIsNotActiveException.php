<?PHP

namespace App\Security\Exceptions;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AccountIsNotActiveException extends AuthenticationException {
    public function getMessageKey(): string {
        return "auth.failed.inactive";
    }
}