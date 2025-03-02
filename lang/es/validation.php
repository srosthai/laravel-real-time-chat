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

    'accepted' => 'Se debe aceptar el atributo :.',
    'active_url' => 'El atributo: no es una URL válida.',
    'after_or_equal' => 'El atributo: debe ser una fecha posterior o igual a: fecha.',
    'alpha' => 'El atributo: solo puede contener letras.',
    'alpha_dash' => 'El atributo: solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El atributo: solo puede contener letras y números.',
    'array' => 'El atributo: debe ser una matriz',
    'before' => 'El atributo: debe ser una fecha anterior a la fecha.',
    'before_or_equal' => 'El atributo: debe ser una fecha anterior o igual a: fecha.',
    'between' => [
        'numeric' => 'El atributo: debe estar entre: min y: max.',
        'file' => 'El atributo: debe estar entre: min y: max kilobytes.',
        'string' => 'El atributo: debe estar entre: min y: max caracteres.',
        'array' => 'El atributo: debe tener entre: min y: max elementos.',
    ],
    'boolean' => 'El campo de :atributo debe ser verdadero o falso.',
    'confirmed' => 'La confirmación del atributo: no coincide.',
    'date' => 'El :atributo no es una fecha válida.',
    'date_equals' => 'El :atributo debe ser una fecha igual a: fecha.',
    'date_format' => 'El :atributo no coincide con el formato: formato.',
    'different' => 'El :atributo y: other deben ser diferentes.',
    'digits' => 'El :atributo debe ser :dígitos dígitos.',
    'digits_between' => 'El :atributo debe estar entre: min y: max dígitos.',
    'dimensions' => 'El :atributo tiene dimensiones de imagen no válidas.',
    'distinct' => 'El  :atributo campo de tiene un valor duplicado.',
    'email' => 'El :atributo debe ser una dirección de correo electrónico válida.',
    'ends_with' => 'El atributo: debe terminar con uno de los siguientes :valores',
    'exists' => 'The selected: attribute is invalid.',
    'file' => 'El :atributo debe ser un archivo.',
    'filled' => 'El :atributo debe campo de  tener un valor.',
    'gt' => [
        'numeric' => 'El: atributo debe ser mayor que: valor.',
        'file' => 'El :atributo debe ser mayor que el valor :de kilobytes',
        'string' => 'El: atributo debe ser mayor que: valor caracteres.',
        'array' => 'El :atributo debe tener más de: elementos de valor',
    ],
    'gte' => [
        'numeric' => 'El: atributo debe ser mayor o igual que: valor.',
        'file' => 'El :atributo debe ser mayor o igual que: valor kilobytes.',
        'string' => 'El :atributo debe ser mayor o igual que los caracteres de valor.',
        'array' => 'El :atributo debe tener: valor  elementos   o más.',
    ],
    'image' => 'El :atributo debe ser una imagen.',
    'in' => 'El seleccionado :atributono es válido.',
    'in_array' => 'El :atributo  campo  no existe en: otro.',
    'integer' => 'El :atributo debe ser un número entero.',
    'ip' => 'El :atributo debe ser una dirección IP válida.',
    'ipv4' => 'El :atributo debe ser una dirección IPv4 válida.',
    'ipv6' => 'El :atributo debe ser una dirección IPv6 válida.',
    'json' => 'El :atributo debe ser una cadena JSON válida.',
    'lt' => [
        'numeric' => 'El: atributo debe ser menor que: valor.',
        'file' => 'El :atributo debe ser menor que: valor kilobytes.',
        'string' => 'El :atributo debe tener menos de: valores de caracteres.',
        'array' => 'El atributo: debe tener menos de: elementos de valor.',
    ],
    'lte' => [
        'numeric' => 'El: atributo debe ser menor o igual que: valor.',
        'file' => 'El atributo: debe ser menor o igual que: valor kilobytes.',
        'string' => 'El :atributo debe ser menor o igual que los caracteres de valor.',
        'array' => 'El :atributo no debe tener más de:  valor artículos.',
    ],
    'max' => [
        'numeric' => 'El :atributo no puede ser mayor que: máx.',
        'file' => 'El atributo: no puede ser mayor que: máximo kilobytes.',
        'string' => 'El atributo: no puede ser mayor que: máximo de caracteres.',
        'array' => 'El atributo: no puede ser mayor que: máximo de caracteres.',
    ],
    'mimes' => 'El atributo: debe ser un archivo de tipo: valores.',
    'mimetypes' => 'El atributo: debe ser un archivo de tipo: valores',
    'min' => [
        'numeric' => 'El :atributo debe ser al menos: min.',
        'file' => 'El atributo: debe tener al menos: min kilobytes',
        'string' => 'El atributo: debe tener al menos: min caracteres',
        'array' => 'El atributo: debe tener al menos :artículos mínimos',
    ],
    'not_in' => 'El seleccionado :atributo no es válido.',
    'not_regex' => 'El :formato de atributo no es válido.',
    'numeric' => 'El :atributo debe ser un número.',
    'present' => 'El :campo de atributo debe estar presente.',
    'regex' => 'El :formato de atributo no es válido.',
    'required' => 'El :campo de atributo es obligatorio.',
    'required_if' => 'El :campo de atributo es obligatorio cuando: otro es: valor.',
    'required_unless' => 'El :campo de atributo es obligatorio a menos que: otro esté en: valores.',
    'required_with' => 'El :campo de atributo es obligatorio cuando: valores está presente.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'El :campo de atributo es obligatorio cuando: hay valores presentes.',
    'required_without_all' => 'El :campo de atributo es obligatorio cuando ninguno de los valores: está presente.',
    'same' => 'El :atributo y: otro deben coincidir.',
    'size' => [
        'numeric' => 'El :atributo debe ser :tamaño.',
        'file' => 'El :atributo debe ser: tamaño kilobytes.',
        'string' => 'El :atributo debe ser:  tamaño caracteres. ',
        'array' => 'El :atributo debercontener: artículos de tamaño.',
    ],
    'starts_with' => 'El :atributo debe comenzar con uno de los siguientes valores:',
    'string' => 'El atributo: debe ser una cadena.',
    'timezone' => 'El :atributo debe ser una zona válida.',
    'unique' => 'El :atributo ya se ha tomado.',
    'uploaded' => 'El :atributo  fallida para subir.',
    'url' => 'El :formato de atributo no es válido.',
    'uuid' => 'El: atributo debe ser un UUID válido.',

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
            'rule-name' => 'mensaje costosom',
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
