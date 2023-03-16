<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use Ustabor\Infrastructure\Files\UserAvatar;
use Ustabor\Infrastructure\Enums\User\UserGender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gsdk\DateTime;

class AccountController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        $valueEmpty = '<i class="value-empty">Не указано</i>';

        return Layout::title($user->presentation)
            ->ss('account')
            ->view('account.settings', [
                'title' => $user->presentation,
                'style' => 'account',
                'user' => $user,
                'valueEmpty' => $valueEmpty
            ]);
    }

    public function name(Request $request)
    {
        $form = Form::text('presentation', ['label' => 'Имя в системе', 'required' => true])
            ->text('name', ['label' => 'Имя'])
            ->text('surname', ['label' => 'Фамилия']);

        if ($request->isMethod('get')) {
            $form->setData(Auth::user()->toArray());
        } elseif ($form->submit()) {
            $data = $form->getData();
            $user = Auth::user();
            $user->fill($data);
            $user->save();
            return json_reload();
        }

        return view('account.form', [
            'title' => 'Имя',
            'description' => 'Ваше имя по которому другие пользователи будут к Вам обращаться',
            'form' => $form
        ]);
    }

    public function birthday(Request $request)
    {
        $now = CurrentDate();

        $days = [];
        for ($i = 1; $i <= 31; $i++) {
            $days[$i] = $i;
        }

        static $months = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        ];

        $y = (int)$now->format('Y');
        $years = [];
        for ($i = 0; $i < 100; $i++) {
            $years[$y - $i] = $y - $i;
        }

        $form = new Form('data');
        $form
            ->addElement('day', 'select', [
                'label' => 'День',
                'emptyItem' => '',
                'items' => $days
            ])
            ->addElement('month', 'select', [
                'label' => 'Месяц',
                'emptyItem' => '',
                'items' => $months
            ])
            ->addElement('year', 'select', [
                'label' => 'Год',
                'emptyItem' => '',
                'items' => $years
            ]);

        if ($request->isMethod('get')) {
            $bd = Auth::user()->birthday;
            if ($bd) {
                $dt = new DateTime($bd);
                $form->setData([
                    'day' => $dt->getDay(),
                    'month' => $dt->getMonth(),
                    'year' => $dt->getYear()
                ]);
            }
        } elseif ($form->submit()) {
            $data = $form->getData();

            $hasValue = false;
            $hasEmpty = false;
            foreach (['day', 'month', 'year'] as $k) {
                if ($data[$k]) {
                    $hasValue = true;
                } else {
                    $hasEmpty = true;
                }
            }

            $user = Auth::user();
            if (!$hasEmpty) {
                $now->setDay($data['day']);
                $now->setMonth($data['month']);
                $now->setYear($data['year']);
                $user->birthday = $now->format('Y-m-d');
            } elseif (!$hasValue) {
                $user->birthday = null;
            } else {
                $form->addError('Необходимо заполнить все поля');
            }

            if ($form->isValid()) {
                $user->save();

                return json_reload();
            }
        }

        return view('account.form', [
            'title' => 'Дата рождения',
            'cls' => 'window-birthday',
            'description' => 'Ваша дата рождения может использоваться для защиты аккаунта и персонализации. Если этот аккаунт использует компания или организация, укажите дату рождения сотрудника, который им управляет.',
            'form' => $form
        ]);
    }

    public function gender(Request $request)
    {
        $form = new Form('data');
        $form
            ->addElement('gender', 'radio', [
                //'label' => 'Имя в системе',
                'items' => [
                    0 => 'Не указано',
                    UserGender::MALE => UserGender::getLabel(UserGender::MALE),
                    UserGender::FEMALE => UserGender::getLabel(UserGender::FEMALE)
                ]
            ]);

        if ($request->isMethod('get')) {
            $form->setData(['gender' => Auth::user()->gender ?? 0]);
        } elseif ($form->submit()) {
            $user = Auth::user();
            $user->gender = $form->getElement('gender')->getValue() ?: null;
            $user->save();
            return json_reload();
        }

        return view('account.form', [
            'title' => 'Пол',
            'description' => 'Сведения о вашем поле помогут нам правильно к вам обращаться.',
            'form' => $form
        ]);
    }

    public function email(Request $request)
    {
        $form = new Form('data');
        $form
            ->addElement('email', 'text', [
                'label' => 'Адрес электронной почты',
                'inputType' => 'email',
                'required' => true
            ]);

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            $data = $form->getData();
            $login = $data['email'];

            if (User::findByLogin($login)) {
                $form->getElement('email')->setError('Данный email уже привязан к аккаунту');
            }

            if ($form->isValid()) {
                $confirmation = app('codeConfirmation');
                if ($confirmation->loginConfirmed($login)) {
                    $confirmation->forget();

                    $user = Auth::user();
                    $user->addEmail($login);

                    return json_reload();
                } elseif (!$confirmation->hasCode($login) && !$confirmation->create($login)) {
                    $form->addError('Не удалось отправить уведомление, проверьте указанный email');
                }

                return self::jsonConfirmation($login);
            }
        }

        return view('account.form', [
            'title' => 'Добавть Email',
            'description' => 'Email будет использоваться для важных уведомлений, входа в аккаунт и не только.',
            'form' => $form
        ]);
    }

    public function phone(Request $request)
    {
        $form = new Form('data');
        $form
            ->addElement('phone', 'text', [
                'label' => 'Телефон',
                'inputType' => 'tel',
                'inputMode' => 'numeric',
                'required' => true
            ]);

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            $data = $form->getData();
            $login = \format\phone($data['phone'], 'contact');

            if (User::findByLogin($login)) {
                $form->getElement('phone')->setError('Данный номером телефона уже привязан к аккаунту');
            }

            if ($form->isValid()) {
                $confirmation = app('codeConfirmation');
                if ($confirmation->loginConfirmed($login)) {
                    $confirmation->forget();

                    $user = Auth::user();
                    $user->addPhone($login);

                    return json_reload();
                } elseif (!$confirmation->hasCode($login) && !$confirmation->create($login)) {
                    $form->addError('Не удалось отправить уведомление, проверьте указанный телефон');
                }

                return self::jsonConfirmation($login);
            }
        }

        return view('account.form', [
            'title' => 'Добавть номер телефона',
            'description' => 'Номер будет использоваться для важных уведомлений, входа в аккаунт и не только.',
            'form' => $form
        ]);
    }

    public function password(Request $request)
    {
        $form = new Form('data');
        $form
            ->addElement('password', 'password', ['label' => 'Новый пароль', 'required' => true])
            ->addElement('confirm', 'password', ['label' => 'Подтвердите пароль', 'required' => true]);

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            $data = $form->getData();
            if ($data['password'] != $data['confirm']) {
                $form->addError('Подтвержден');
            }

            if ($form->isValid()) {
                $user = Auth::user();
                $user->password = $data['password'];
                $user->save();
                return json_reload();
            }
        }

        return view('account.form', [
            'title' => 'Изменить пароль',
            'description' => 'Выберите надежный пароль и не используйте его для других аккаунтов',
            'form' => $form
        ]);
    }

    public function photo(Request $request)
    {
        $form = new Form('data');
        $form
            ->addElement('image', 'text', ['inputType' => 'file', 'accept' => 'image/*']);

        $avatar = UserAvatar::findByEntity(Auth::user());

        if ($request->isMethod('get')) {
        } elseif ($form->submit()) {
            //var_dump($request->file('data.image')->getClientOriginalName());exit;
            /*$request->validate([
                'data.image' => 'required|mimes:jpg,jpeg,png|max:4096'
            ]);*/
            /*$validator = Validator::make($request->all(), [
                'image' => 'required|mimes:jpg,jpeg,png,bmp,tiff |max:4096',
            ], [
                'mimes' => 'Please insert image only',
                'max' => 'Image should be less than 4 MB'
            ]);*/

            //$msg = $validator->errors()
            //if () { {
            if (!$avatar) {
                $avatar = UserAvatar::createFromEntity(Auth::user());
            }

            $avatar->upload($request->file('data.image'));

            return json_reload();
            //}
        }

        return view('account.photo', [
            'title' => 'Фото профиля',
            'description' => 'По фото профиля другие люди смогут вас узнавать, а вам будет проще определять, в какой аккаунт вы вошли.',
            'cls' => 'window-avatar',
            'avatar' => $avatar,
            'form' => $form
        ]);
    }
}
