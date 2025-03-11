<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// Функция для объединения ФИО из частей
function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}

// Функция для разбиения ФИО на части
function getPartsFromFullname($fullname) {
    $parts = explode(' ', $fullname);
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronomyc' => $parts[2]
    ];
}

// Функция для сокращения ФИО
function getShortName($fullname) {
    // Разбиваем ФИО на части
    $parts = getPartsFromFullname($fullname);

    // Сокращаем фамилию до первой буквы и добавляем точку
    $surnameInitial = mb_substr($parts['surname'], 0, 1) . '.';

    // Возвращаем результат "Имя Ф."
    return $parts['name'] . ' ' . $surnameInitial;
}

// Функция для определения пола по ФИО
function getGenderFromName($fullname) {
    // Разбиваем ФИО на части
    $parts = getPartsFromFullname($fullname);

    $genderScore = 0;

    // Проверяем признаки мужского пола
    if (mb_substr($parts['patronomyc'], -2) === 'ич') {
        $genderScore += 1;
    }

    if (mb_substr($parts['name'], -1) === 'й' || mb_substr($parts['name'], -1) === 'н') {
        $genderScore += 1;
    }

    if (mb_substr($parts['surname'], -1) === 'в') {
        $genderScore += 1;
    }

    // Проверяем признаки женского пола
    if (mb_substr($parts['patronomyc'], -3) === 'вна') {
        $genderScore -= 1;
    }

    if (mb_substr($parts['name'], -1) === 'а') {
        $genderScore -= 1;
    }

    if (mb_substr($parts['surname'], -2) === 'ва') {
        $genderScore -= 1;
    }

    // Определяем пол по итоговому значению
    if ($genderScore > 0) {
        return 1; // Мужской пол
    } elseif ($genderScore < 0) {
        return -1; // Женский пол
    } else {
        return 0; // Неопределенный пол
    }
}

foreach ($example_persons_array as $person) {
    // Разбиваем ФИО на части
    $parts = getPartsFromFullname($person['fullname']);
    echo "Разбитое ФИО: ";
    print_r($parts);

    // Собираем ФИО обратно
    $fullname = getFullnameFromParts($parts['surname'], $parts['name'], $parts['patronomyc']);
    echo "Собранное ФИО: " . $fullname . "\n";
}

foreach ($example_persons_array as $person) {
    $shortName = getShortName($person['fullname']);
    echo $shortName . "\n";
}

foreach ($example_persons_array as $person) {
    $gender = getGenderFromName($person['fullname']);
    $genderText = match ($gender) {
        1 => 'мужской пол',
        -1 => 'женский пол',
        0 => 'неопределенный пол',
    };
    echo $person['fullname'] . ' - ' . $genderText . "\n";
}