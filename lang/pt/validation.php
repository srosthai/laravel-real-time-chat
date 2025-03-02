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

    'accepted' => 'O: atributo deve ser aceito.',
    'active_url' => 'O :atributo não é um URL válido.',
    'after' => 'O: atributo deve ser uma data após: date.',
    'after_or_equal' => 'O: atributo deve ser uma data posterior ou igual a: date.',
    'alpha' => 'O: atributo só pode conter letras.',
    'alpha_dash' => 'O: atributo só pode conter letras, números, travessões e sublinhados.',
    'alpha_num' => 'O: atributo só pode conter letras e números.',
    'array' => 'O: atributo deve ser uma matriz.',
    'before' => 'O: atributo deve ser uma data anterior a: date.',
    'before_or_equal' => 'O: atributo deve ser uma data anterior ou igual a: date.',
    'between' => [
        'numeric' => 'O: atributo deve estar entre: min e: max.',
        'file' => 'O: atributo deve estar entre: min e: max kilobytes.',
        'string' => 'O: atributo deve ter entre: min e: max caracteres.',
        'array' => 'O: atributo deve ter entre: min e: max itens.',
    ],
    'boolean' => 'O campo: attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação de: attribute não corresponde.',
    'date' => 'O: atributo não é uma data válida.',
    'date_equals' => 'O: atributo deve ser uma data igual a: date.',
    'date_format' => 'O: atributo não corresponde ao formato: format.',
    'different' => 'O: atributo e: other devem ser diferentes.',
    'digits' => 'O: atributo deve ser: dígitos dígitos.',
    'digits_between' => 'O: atributo deve ter entre: min e: max dígitos.',
    'dimensions' => 'O: atributo tem dimensões de imagem inválidas.',
    'distinct' => 'O campo: atributo tem um valor duplicado.',
    'email' => 'O: atributo deve ser um endereço de e-mail válido.',
    'ends_with' => 'O: atributo deve terminar com um dos seguintes:: valores',
    'exists' => 'O selecionado :atributo  é inválido.',
    'file' => 'O: atributo deve ser um arquivo.',
    'filled' => 'O campo: attribute deve ter um valor.',
    'gt' => [
        'numeric' => 'O: atributo deve ser maior que: value.',
        'file' => 'O: atributo deve ser maior que: value kilobytes.',
        'string' => 'O: atributo deve ser maior que: caracteres de valor.',
        'array' => 'O: atributo deve ter mais do que: itens de valor.',
    ],
    'gte' => [
        'numeric' => 'O: atributo deve ser maior ou igual: value.',
        'file' => 'O: atributo deve ser maior ou igual: value kilobytes.',
        'string' => 'O: atributo deve ser maior ou igual a caracteres de valor.',
        'array' => 'O: atributo deve ter: itens de valor ou mais.',
    ],
    'image' => 'O: atributo deve ser uma imagem.',
    'in' => 'O selecionado  :atributo é inválido.',
    'in_array' => 'O campo: attribute não existe em: other.',
    'integer' => 'O: atributo deve ser um número inteiro.',
    'ip' => 'O: atributo deve ser um endereço IP válido.',
    'ipv4' => 'O: atributo deve ser um endereço IPv4 válido.',
    'ipv6' => 'O: atributo deve ser um endereço IPv6 válido.',
    'json' => 'O: atributo deve ser uma string JSON válida.',
    'lt' => [
        'numeric' => 'O: atributo deve ser menor que: value.',
        'file' => 'O: atributo deve ser menor que: value kilobytes.',
        'string' => 'O: atributo deve ter menos que: caracteres de valor.',
        'array' => 'O: atributo deve ter menos do que: itens de valor.',
    ],
    'lte' => [
        'numeric' => 'O: atributo deve ser menor ou igual: value.',
        'file' => 'O: atributo deve ser menor ou igual: value kilobytes.',
        'string' => 'O: atributo deve ser menor ou igual a caracteres de valor.',
        'array' => 'O: atributo não deve ter mais do que: itens de valor.',
    ],
    'max' => [
        'numeric' => 'O: atributo não pode ser maior que: max.',
        'file' => 'O: atributo não pode ser maior que: max kilobytes.',
        'string' => 'O: atributo não pode ser maior que: max caracteres.',
        'array' => 'O: atributo não pode ter mais do que: max itens.',
    ],
    'mimes' => 'O: atributo deve ser um arquivo do tipo:: values.',
    'mimetypes' => 'O: atributo deve ser um arquivo do tipo:: values.',
    'min' => [
        'numeric' => 'O: atributo deve ser pelo menos: min.',
        'file' => 'O: atributo deve ter pelo menos: min kilobytes.',
        'string' => 'O: atributo deve ter pelo menos: min caracteres.',
        'array' => 'O: atributo deve ter pelo menos: min itens.',
    ],
    'not_in' => 'O atributo selecionado: é inválido.',
    'not_regex' => 'O formato: atributo é inválido.',
    'numeric' => 'O: atributo deve ser um número.',
    'present' => 'O campo: atributo deve estar presente.',
    'regex' => 'O formato: atributo é inválido.',
    'required' => 'O campo: atributo é obrigatório.',
    'required_if' => 'O campo: atributo é obrigatório quando: other for: value.',
    'required_unless' => 'O campo: atributo é obrigatório, a menos que: other esteja em: values.',
    'required_with' => 'O campo: atributo é obrigatório quando: values estiver presente.',
    'required_with_all' => 'O campo: atributo é obrigatório quando: valores estão presentes.',
    'required_without' => 'O campo: atributo é obrigatório quando: values não estiver presente.',
    'required_without_all' => 'O campo: atributo é obrigatório quando nenhum dos valores: está presente.',
    'same' => 'O: atributo e: other devem corresponder.',
    'size' => [
        'numeric' => 'O: atributo deve ser: size.',
        'file' => 'O: atributo deve ter: size kilobytes.',
        'string' => 'O: atributo deve ter: caracteres de tamanho.',
        'array' => 'O: atributo deve conter: itens de tamanho.',
    ],
    'starts_with' => 'O: atributo deve começar com um dos seguintes:: valores',
    'string' => 'O: atributo deve ser uma string.',
    'timezone' => 'O: atributo deve ser uma zona válida.',
    'unique' => 'O: atributo já foi usado.',
    'uploaded' => 'O: atributo falhou ao carregar.',
    'url' => 'O formato: atributo é inválido.',
    'uuid' => 'O: atributo deve ser um UUID válido.',

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
            'rule-name' => 'mensagem personalizada',
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
