<?PHP

namespace App\Security\Exceptions;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AccountBlockedException extends AuthenticationException {
    public function getMessageKey(): string {
        return "auth.failed.blocked";
    }
}