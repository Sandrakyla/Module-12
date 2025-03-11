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

// Функция для определения полового состава аудитории
function getGenderDescription($personsArray) {
    // Подсчитываем количество людей каждого пола
    $maleCount = 0;
    $femaleCount = 0;
    $unknownCount = 0;

    foreach ($personsArray as $person) {
        $gender = getGenderFromName($person['fullname']);
        if ($gender === 1) {
            $maleCount++;
        } elseif ($gender === -1) {
            $femaleCount++;
        } else {
            $unknownCount++;
        }
    }

    // Общее количество людей
    $totalCount = count($personsArray);

    // Вычисляем процентное соотношение
    $malePercentage = round(($maleCount / $totalCount) * 100, 1);
    $femalePercentage = round(($femaleCount / $totalCount) * 100, 1);
    $unknownPercentage = round(($unknownCount / $totalCount) * 100, 1);

    // Формируем результат
    $result = "Гендерный состав аудитории:\n";
    $result .= "---------------------------\n";
    $result .= "Мужчины - $malePercentage%\n";
    $result .= "Женщины - $femalePercentage%\n";
    $result .= "Не удалось определить - $unknownPercentage%\n";

    return $result;
}

// Функция для поиска идеальной пары
function getPerfectPartner($surname, $name, $patronomyc, $personsArray) {

    $surname = mb_convert_case($surname, MB_CASE_TITLE, "UTF-8");
    $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE, "UTF-8");

    // Склеиваем ФИО
    $fullname = getFullnameFromParts($surname, $name, $patronomyc);

    // Определяем пол
    $gender = getGenderFromName($fullname);

    // Ищем партнера противоположного пола
    do {

        $randomIndex = array_rand($personsArray);
        $partner = $personsArray[$randomIndex];

        // Определяем пол выбранного человека
        $partnerGender = getGenderFromName($partner['fullname']);
    } while ($partnerGender === $gender || $partnerGender === 0);

    // Сокращаем ФИО для вывода
    $shortName = getShortName($fullname);
    $shortPartnerName = getShortName($partner['fullname']);

    // Генерируем случайный процент совместимости
    $compatibilityPercentage = round(rand(5000, 10000) / 100, 2);

    // Формируем результат
    $result = "$shortName + $shortPartnerName =\n";
    $result .= "♡ Идеально на $compatibilityPercentage% ♡\n";

    return $result;
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

echo getGenderDescription($example_persons_array);

echo getPerfectPartner('ИвАнОв', 'иВаН', 'ИвАнОвИч', $example_persons_array);