<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Das Attribut: muss akzeptiert werden.',
    'active_url' => 'Das Attribut: ist keine gültige URL.',
    'after' => 'Das Attribut: muss ein Datum nach: Datum sein.',
    'after_or_equal' => 'Das Attribut: muss ein Datum nach oder gleich: Datum sein.',
    'alpha' => 'Das Attribut: darf nur Buchstaben enthalten.',
    'alpha_dash' => 'Das Attribut: darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => 'Das Attribut: darf nur Buchstaben und Zahlen enthalten.',
    'array' => 'Das Attribut: muss ein Array sein.',
    'before' => 'Das Attribut: muss ein Datum vor: date sein.',
    'before_or_equal' => 'Das Attribut: muss ein Datum vor oder gleich: date sein.',
    'between' => [
        'numeric' => 'Das Attribut: muss zwischen: min und: max liegen.',
        'file' => 'Das Attribut: muss zwischen: min und: max Kilobyte liegen.',
        'string' => 'Das Attribut: muss zwischen den Zeichen: min und: max liegen.',
        'array' => 'Das Attribut: muss zwischen den Elementen liegen: min und: max.',
    ],
    'boolean' => 'Das Attributfeld: muss wahr oder falsch sein.',
    'confirmed' => 'Die Bestätigung des Attributs: stimmt nicht überein.',
    'date' => 'Das Attribut: ist kein gültiges Datum.',
    'date_equals' => 'Das Attribut: muss ein Datum sein, das dem Datum: entspricht.',
    'date_format' => 'Das Attribut: stimmt nicht mit dem Format: Format überein.',
    'different' => 'Das Attribut: und: other muss unterschiedlich sein.',
    'digits' => 'Das Attribut: muss sein: Ziffern Ziffern.',
    'digits_between' => 'Das Attribut: muss zwischen den Ziffern: min und: max liegen.',
    'dimensions' => 'Das Attribut: hat ungültige Bildabmessungen.',
    'distinct' => 'Das Attributfeld: hat einen doppelten Wert.',
    'email' => 'Das Attribut: muss eine gültige E-Mail-Adresse sein.',
    'ends_with' => 'The: attribute must end with one of the following:: values',
    'exists' => 'Das Attribut ausgewählt: ist ungültig.',
    'file' => 'Das Attribut: muss eine Datei sein.',
    'filled' => 'Das Attributfeld: muss einen Wert haben.',
    'gt' => [
        'numeric' => 'Das Attribut: muss größer als: value sein.',
        'file' => 'Das Attribut: muss größer sein als: Wert Kilobyte.',
        'string' => 'Das Attribut: muss größer sein als: value-Zeichen.',
        'array' => 'Das Attribut: muss mehr als: value-Elemente enthalten.',
    ],
    'gte' => [
        'numeric' => 'Das Attribut: muss größer oder gleich: value sein.',
        'file' => 'Das Attribut: muss größer oder gleich: Wert Kilobyte sein.',
        'string' => 'Das Attribut: muss größer oder gleich: Wertzeichen sein.',
        'array' => 'Das Attribut: muss mindestens: value-Elemente enthalten.',
    ],
    'image' => 'Das Attribut: muss ein Bild sein.',
    'in' => 'Das Attribut selected: ist ungültig.',
    'in_array' => 'Das Attributfeld: existiert nicht in: other.',
    'integer' => 'Das Attribut: muss eine Ganzzahl sein.',
    'ip' => 'Das Attribut: muss eine gültige IP-Adresse sein.',
    'ipv4' => 'Das Attribut: muss eine gültige IPv4-Adresse sein.',
    'ipv6' => 'Das Attribut: muss eine gültige IPv6-Adresse sein.',
    'json' => 'Das Attribut: muss eine gültige JSON-Zeichenfolge sein.',
    'lt' => [
        'numeric' => 'Das Attribut: muss kleiner als: value sein.',
        'file' => 'Das Attribut: muss kleiner als: Wert Kilobyte sein.',
        'string' => 'Das Attribut: muss kleiner sein als: value-Zeichen.',
        'array' => 'Das Attribut: muss weniger als: value-Elemente enthalten.',
    ],
    'lte' => [
        'numeric' => 'Das Attribut: muss kleiner oder gleich: value sein.',
        'file' => 'Das Attribut: muss kleiner oder gleich: Wert Kilobyte sein.',
        'string' => 'Das Attribut: muss kleiner oder gleich: Wertzeichen sein.',
        'array' => 'Das Attribut: darf nicht mehr als: value-Elemente enthalten.',
    ],
    'max' => [
        'numeric' => 'Das Attribut: darf nicht größer sein als: max.',
        'file' => 'Das Attribut: darf nicht größer als: max Kilobyte sein.',
        'string' => 'Das Attribut: darf nicht größer als: max Zeichen sein.',
        'array' => 'Das Attribut: darf nicht mehr als: max Elemente enthalten.',
    ],
    'mimes' => 'Das Attribut: muss eine Datei vom Typ :: values sein.',
    'mimetypes' => 'Das Attribut: muss eine Datei vom Typ :: values sein.',
    'min' => [
        'numeric' => 'Das Attribut: muss mindestens: min sein.',
        'file' => 'Das Attribut: muss mindestens: min Kilobyte betragen.',
        'string' => 'Das Attribut: muss mindestens: min Zeichen enthalten.',
        'array' => 'Das Attribut: muss mindestens: min Elemente enthalten.',
    ],
    'not_in' => 'Das Attribut selected: ist ungültig.',
    'not_regex' => 'Das Attributformat: ist ungültig.',
    'numeric' => 'Das Attribut: muss eine Zahl sein.',
    'present' => 'Das Attributfeld: muss vorhanden sein.',
    'regex' => 'Das Attributformat: ist ungültig.',
    'required' => 'Das Feld: Attribut ist erforderlich.',
    'required_if' => 'Das Feld: attribute ist erforderlich, wenn: other: value ist.',
    'required_unless' => 'Das Feld: attribute ist erforderlich, es sei denn: other befindet sich in: values.',
    'required_with' => 'Das Attributfeld: ist erforderlich, wenn: values vorhanden ist.',
    'required_with_all' => 'Das Attributfeld: ist erforderlich, wenn: Werte vorhanden sind.',
    'required_without' => 'Das Feld: Attribut: ist erforderlich, wenn: Werte nicht vorhanden sind.',
    'required_without_all' => 'Das Feld: Attribut ist erforderlich, wenn keiner der Werte vorhanden ist.',
    'same' => 'Das Attribut: und und other müssen übereinstimmen.',
    'size' => [
        'numeric' => 'Das Attribut: muss: size sein.',
        'file' => 'Das Attribut: muss: Größe Kilobyte sein.',
        'string' => 'Das Attribut: muss Zeichen der Größe: sein.',
        'array' => 'Das Attribut: muss Elemente der Größe: enthalten.',
    ],
    'starts_with' => 'Das Attribut: muss mit einem der folgenden Werte beginnen :: Werte',
    'string' => 'Das Attribut: muss eine Zeichenfolge sein.',
    'timezone' => 'Das Attribut: muss eine gültige Zone sein.',
    'unique' => 'Das Attribut: wurde bereits übernommen.',
    'uploaded' => 'Das Attribut: konnte nicht hochgeladen werden.',
    'url' => 'Das Attributformat: ist ungültig.',
    'uuid' => 'Das Attribut: muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'benutzerdefinierte Nachricht',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
