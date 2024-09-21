<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\CreateUserAction;
use App\DataTransferObjects\CreateUserData;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\form;
use function Laravel\Prompts\info;

final class CreateUserCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * @var string
     */
    protected $description = 'Creates new user';

    public function __construct(
        private readonly CreateUserAction $createUserAction,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $data = form()
            ->text(
                label: 'What is your name?',
                required: true,
                validate: ['required', 'string', 'max:255'],
                name: 'name',
            )
            ->text(
                label: 'What is your email?',
                required: true,
                validate: ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                name: 'email',
            )
            ->password(
                label: 'What is your password?',
                required: true,
                validate: ['password' => Password::default()],
                hint: 'Minimum 8 characters.',
                name: 'password',
            )
            ->select(
                label: 'What role should the user have?',
                options: ['admin', 'editor'],
                name: 'role',
            )
            ->submit();

        $role = Role::whereName($data['role'])->first();

        if ($role === null) {
            $this->error('Role not found');

            return -1;
        }

        $user = $this->createUserAction->execute(
            new CreateUserData(
                $data['name'],
                $data['email'],
                $data['password'],
                $role,
            )
        );

        info(sprintf('User "%s" created successfully.', $user->email));

        return 1;
    }
}
