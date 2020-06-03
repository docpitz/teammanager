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

    'accepted' => ':attribute muss akzeptiert werden.',
    'active_url' => ':attribute ist keine gültige URL.',
    'after' => ':attribute muss einem Datum nach :date entsprechen.',
    'after_or_equal' => ':attribute muss ein Datum ab dem :date sein.',
    'alpha' => ':attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => ':attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => ':attribute darf nur Buchstaben und Zahlen enthalten.',
    'array' => ':attribute muss eine Aufreihung sein.',
    'before' => ':attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Datum vom Feld :attribute muss vor oder gleich dem :date sein.',
    'between' => [
        'numeric' => ':attribute muss zwischen :min und :max liegen.',
        'file' => ':attribute muss zwischen :min und :max Kilobytes liegen.',
        'string' => ':attribute muss zwischen :min und :max Buchstaben lang sein.',
        'array' => ':attribute muss zwischen :min und :max Zeichen lang sein.',
    ],
    'boolean' => ':attribute muss ja oder nein sein.',
    'confirmed' => 'Die Bestätigung von :attribute stimmt nicht überein.',
    'date' => ' :attribute ist kein gültiges Datum.',
    'date_equals' => ':attribute muss der :date sein.',
    'date_format' => ':attribute stimmt nicht mit dem :format Format überein.',
    'different' => ':attribute und :other müssen unterschiedlich sein.',
    'different_ignore_case' => ':attribute und :other müssen unterschiedlich sein.',
    'digits' => ':attribute muss aus :digits Ziffern bestehen.',
    'digits_between' => ':attribute muss zwischen :min und :max Ziffern lang sein.',
    'dimensions' => ':attribute hat eine ungültige Bildgröße.',
    'distinct' => ':attribute hat einen doppelten Wert.',
    'email' => ':attribute muss eine gültige EMail Adresse sein.',
    'exists' => 'Das ausgewählte Feld :attribute ist ungültig.',
    'file' => ':attribute muss eine Datei sein.',
    'filled' => ':attribute muss einen Wert haben.',
    'gt' => [
        'numeric' => ':attribute muss größer sein als :value.',
        'file' => ':attribute muss größer als :value Kilobytes sein.',
        'string' => ':attribute muss länger als :value Buchstaben sein.',
        'array' => ':attribute muss aus mehr als :value Zeichen bestehen.',
    ],
    'gte' => [
        'numeric' => ':attribute muss größer oder gleichwertig zu :value sein.',
        'file' => ':attribute muss aus mindestens :value bestehen.',
        'string' => ':attribute muss aus mindestens :value Buchstaben bestehen.',
        'array' => ':attribute muss aus mindestens :value Zeichen bestehen.',
    ],
    'image' => ':attribute muss ein Bild sein.',
    'in' => 'Das ausgewählte Feld :attribute ist ungültig.',
    'in_array' => ':attribute existiert in :other leider nicht.',
    'integer' => ':attribute muss eine ganze Zahl sein.',
    'ip' => ':attribute muss eine gültige IP-Adresse sein.',
    'ipv4' => ':attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6' => ':attribute muss eine gültige IPv6-Adresse sein.',
    'json' => ':attribute muss ein gültiger JSON-String sein.',
    'lt' => [
        'numeric' => ':attribute muss weniger sein als :value.',
        'file' => ':attribute muss kleiner sein als :value Kilobytes.',
        'string' => ':attribute muss kürzer sein als :value Buchstaben.',
        'array' => ':attribute muss weniger als :value Zeichen haben.',
    ],
    'lte' => [
        'numeric' => ':attribute darf aus maximal :value bestehen.',
        'file' => ':attribute darf maximal :value Kilobytes groß sein.',
        'string' => ':attribute darf aus maximal :value Buchstaben bestehen.',
        'array' => ':attribute darf aus maximal :value Zeichen bestehen.',
    ],
    'max' => [
        'numeric' => 'Das Feld :attribute darf nicht größer als :max sein.',
        'file' => 'Das Feld :attribute darf :max Kilobytes nicht überschreiten.',
        'string' => 'Das Feld :attribute darf :max Zeichen nicht überschreiten.',
        'array' => 'Das Feld :attribute darf :max Elementen nicht überschreiten.',
    ],
    'mimes' => ':attribute muss eine type: :values Datei sein.',
    'mimetypes' => 'Das :attribute muss eine type: :values Datei sein.',
    'min' => [
        'numeric' => 'Das Feld :attribute muss mindestens :min betragen.',
        'file' => 'Das Feld :attribute muss mindestens :min Kilobytes betragen.',
        'string' => 'Das Feld :attribute muss mindestens :min Zeichen enthalten.',
        'array' => 'Das Feld :attribute muss mindestens :min Elemente enthalten.',
    ],
    'not_in' => 'Das ausgewählte Feld :attribute ist ungültig.',
    'not_regex' => 'Das Format des Feldes :attribute ist ungültig.',
    'numeric' => 'Das Feld :attribute muss eine Zahl sein.',
    'present' => 'Das Feld :attribute muss sichtbar sein.',
    'regex' => 'Das Format des Feldes :attribute ist gültig.',
    'required' => 'Das Feld :attribute ist erforderlich.',
    'required_if' => 'Das Feld:attribute ist nicht erforderlich, wenn :other :value entspricht.',
    'required_unless' => 'Das Feld :attribute ist nicht erforderlich, solange :other Teil von :values ist.',
    'required_with' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden sind.',
    'required_without' => 'Das Feld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Feld :attribute ist erforderlich, wenn keine der :values vorhanden sind.',
    'same' => ':attribute und :other müssen übereinstimmen.',
    'size' => [
        'numeric' => ':attribute muss :size groß sein.',
        'file' => ':attribute muss :size Kilobytes groß sein.',
        'string' => ':attribute muss :size Buchstaben lang sein.',
        'array' => ':attribute muss :size Zeichen lang sein.',
    ],
    'starts_with' => 'Das Feld :attribute muss mit einem der folgenden: :values beginnen',
    'string' => 'Das Feld :attribute muss eine Zeichenkette sein.',
    'timezone' => 'Das Feld :attribute muss in einem gültigen Rahmen liegen.',
    'unique' => ':attribute ist bereits vergeben.',
    'uploaded' => ':attribute konnte nicht hochgeladen werden.',
    'url' => 'Das Format des Feldes :attribute ist ungültig.',
    'uuid' => 'Das Feld :attribute muss eine gültige UUID sein.',

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
            'rule-name' => 'custom-message',
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

    'attributes' => [

        // general
        'name' => 'Name',
        'description' => 'Beschreibung',
        // group, role

        // user
        'username' => 'Benutzername',
        'firstname' => 'Vorname',
        'surname' => 'Nachname',
        'email' => 'E-Mail-Adresse',
        'email_optional' => 'Weitere E-Mail-Adresse',
        'password' => 'Passwort',
        'role_name' => 'Nutzerrolle',

        // profile
        'photo' => 'Foto',
        'old_password' => 'Aktuelles Passwort',
        'password_confirmation' => 'Passwort wiederholen',

        // event
        'score' => 'Mitmachpunkte',
        'max_participant' => 'Max. Teilnehmerzahl',
        'meeting_place' => 'Veranstaltungsort / Treffpunkt',
        'date_event_range' => 'Veranstaltungszeitraum',
        'date_sign_up_range' => 'Anmeldezeitraum',
        'date_publication' => 'Veröffentlichungsdatum',



    ],

];
