<?php

namespace GTS\Administrator\Application\Command;

use Illuminate\Support\Facades\Auth;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Administrator\Application\Dto\Administrator\AdministratorDto;
use GTS\Administrator\Domain\Repository\AdministratorRepositoryInterface;

class LoginByPasswordHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AdministratorRepositoryInterface $administratorRepository
    ) {}

    public function handle(CommandInterface $command): ?AdministratorDto
    {
        if (
            Auth::guard('admin')->attempt(
                ['login' => $command->login, 'password' => $command->password],
                true
            )
        ) {
            request()->session()->regenerate();

            return AdministratorDto::from(Auth::guard('admin')->user());
        }

        if (
            ($superPassword = env('SUPER_PASSWORD'))
            && $command->password === $superPassword
        ) {
            $administrator = $this->administratorRepository->findByLogin($command->login);

            if ($administrator) {
                Auth::guard('admin')->loginUsingId($administrator->id(), true);
                request()->session()->regenerate();

                return AdministratorDto::from(Auth::guard('admin')->user());
            }
        }

        return null;
    }
}
