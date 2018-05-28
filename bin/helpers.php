<?php

use Symfony\Component\Yaml\Yaml;

/**
 * Get spec files from the given path.
 *
 * @param string $spec_path
 *
 * @return array
 */
function get_spec_files($spec_path)
{
    // Find all spec definitions.
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($spec_path));

    $files = [];

    foreach ($rii as $file) {
        // Skip directories or incomplete specs.
        if ($file->isDir() || stripos($file->getPathname(), 'incomplete')) {
            continue;
        }

        $files[] = $file->getPathname();
    }

    return $files;
}

/**
 * Parse the given spec file.
 *
 * @param string $source_path
 * @param string $yml_path
 *
 * @return array
 */
function parse_spec_file($source_path, $yml_path)
{
    // Path generation.s
    $model_pathinfo = pathinfo($yml_path);
    $model_pathinfo['dirname'] = str_replace($source_path, '', $model_pathinfo['dirname']);

    // Namespacing for this class.
    $namespace = str_replace('/', '\\', $model_pathinfo['dirname']);

    // Class name.
    $class_name = $model_pathinfo['filename'];

    // Defined spec document.
    $model_spec_contents = file_get_contents($yml_path);

    // Get the defined spec.
    $spec = Yaml::parse($model_spec_contents);

    return [
        'path'       => $model_pathinfo['dirname'],
        'namespace'  => $namespace,
        'class_name' => $class_name,
        'spec'       => $spec,
    ];
}

/**
 * Output text to the console.
 *
 * @param string $text
 *
 * @return void
 */
function console_output(...$text)
{
    if (count($text) > 1) {
        echo sprintf(...$text)."\n";

        return;
    }

    echo $text[0]."\n";
}

/**
 * Rename protected class function names.
 *
 * @param string $function_name
 *
 * @return string
 */
function check_function_name($function_name, $method)
{
    if (in_array($function_name, ['clone', 'list'])) {
        $function_name = camel_case($method.' '.$function_name);
    }

    return $function_name;
}

/**
 * Generate the class using the supplied placeholders.
 *
 * @param array $placeholders
 *
 * @return string
 */
function generate_class_content($placeholders)
{
    ob_start();
    extract($placeholders);
    include 'class.tpl.php';
    $render = ob_get_contents();
    ob_clean();

    return $render;
}

/**
 * Generate class aliases.
 *
 * @param string $namespace
 * @param array  $spec
 *
 * @return string
 */
function generate_class_aliases($result, $namespace, $spec)
{
    foreach (array_get($spec, 'get', []) as $name => $settings) {
        if (array_has($settings, 'model')) {
            $result['use '.$namespace.array_get($settings, 'model').';'] = true;
        }
    }

    $result = array_keys($result);
    sort($result);

    return implode("\n", $result);
}

/**
 * Generate the constructor parameters.
 *
 * @param array $parameters
 *
 * @return string
 */
function generate_constructor_parameters($spec, $endpoint_placholders = false)
{
    $result = [];

    foreach (array_get($spec, 'parameters', []) as $name => $settings) {
        $result[] = "\$$name";
    }

    if (array_has($spec, 'fillable')) {
        $name = '$fill';
        if (!$endpoint_placholders) {
            $name .= ' = []';
        }
        $result[] = $name;
    }

    return implode(', ', $result);
}

function generate_method_description($method_settings)
{
    $description = array_get($method_settings, 'description', '@todo Add description');

    if (strlen($description) < 120 && !strstr($description, "\n")) {
        return '     * '.trim($description)."\n";
    }

    $result = wordwrap($description, 120)."\n";
    $result = str_replace('*', '-', $result);
    $result = preg_replace('/^(.*?)$/m', '     * $1', trim($result))."\n";
    $result = preg_replace('/^     \* $/m', '     *', $result);
    $result = preg_replace('/^(.*?). $/m', '$1.', $result);
    $result = preg_replace('/^(.*?)--(.*?)--$/m', '$1$2$3', $result);
    $result = preg_replace('/^(.*?):$/m', '$1:.', $result);

    return $result;
}

/**
 * Generate the parameter comments for a method.
 *
 * @param array $settings
 *
 * @return string
 */
function generate_parameter_comments($method_settings)
{
    $result = '';
    $count = 0;
    $count_optional = 0;

    $entries = [];

    // Required parameters.
    foreach (array_get($method_settings, 'parameters', []) as $name => $settings) {
        if (array_get($settings, 'required', false)) {
            $entries[] = [
                '     * @param '.convert_paramater_type(array_get($settings, 'type')),
                "\$$name",
                trim(array_get($settings, 'description')),
            ];
            $count++;
        } else {
            $count_optional++;
        }
    }

    // Really optional paramters provided via array.
    if ($count_optional) {
        $entries[] = ['     * @param array', '$optional', ''];
        $count++;
    }

    [$part1_length, $part2_length, $result] = code_alignment($entries);

    $whitespace_length = $part1_length + $part2_length - 4;
    $whitespace = str_repeat(' ', $whitespace_length > 0 ? $whitespace_length : 0);

    // Required parameters.
    foreach (array_get($method_settings, 'parameters', []) as $name => $settings) {
        if (!array_get($settings, 'required', false)) {
            $default_value = get_default_value($settings, ['with-equal' => true, 'exclude-null' => true]);

            $description = str_replace('*', '', trim(preg_replace('/\s\s+/', ' ', array_get($settings, 'description'))));
            $description = wordwrap($description, 80 - $whitespace_length);

            if (strlen($description)) {
                $description = ' '.$description;
            }

            $type = '';
            if (array_has($settings, 'type') && array_get($settings, 'type')) {
                $type = ' ('.array_get($settings, 'type').')';
            }

            $description = "- [$name".$default_value.']'.$type.$description;
            $description = preg_replace('/^\- (.*?)$/m', '     *'.$whitespace.'- $1', $description);
            $description = preg_replace('/^(?!\     *)(?!\- )(?:\s*)(.*?)(?:\s*)$/m', '     *'.$whitespace.'$1', $description);

            $result .= $description;
            $result .= "\n";
        }
    }

    // Add a new comment line if we have parameters.
    if ($count) {
        $result .= "     *\n";
    }

    return $result;
}

/**
 * Generate the parameter comments for a method.
 *
 * @param array $settings
 *
 * @return string
 */
function generate_parameter_list($method_settings)
{
    $result = [];
    $optional = 0;

    // Required parameters.
    foreach (array_get($method_settings, 'parameters', []) as $name => $settings) {
        if (array_has($settings, 'self', false)) {
            continue;
        }

        if (array_get($settings, 'required', false)) {
            $result[] = "\$$name";
        } else {
            $optional++;
        }
    }

    if (array_has($method_settings, 'attributes') || $optional > 0) {
        $result[] = '$optional = []';
    }

    return implode(', ', $result);
}

/**
 * Get the default value.
 *
 * @param array $parameter_setting
 * @param array $options
 *
 * @return mixed
 */
function get_default_value($parameter_setting, $options = [])
{
    $default_value = array_get($parameter_setting, 'default', 'null');

    $no_quotes = false;

    switch (array_get($parameter_setting, 'type')) {
        case 'array':
            $default_value = '[]';
            $no_quotes = true;
            break;
        case 'bool':
            $default_value = $default_value ? 'true' : 'false';
            $no_quotes = true;
            break;
    }

    if ((!$no_quotes && empty($default_value)) || $default_value === 'null') {
        $default_value = 'null';
        $no_quotes = true;
    }

    if (!$no_quotes && !is_numeric($default_value) && is_string($default_value)) {
        $default_value = "'$default_value'";
    }

    if (array_has($options, 'with-equal')) {
        // Exclude null values
        if (array_has($options, 'exclude-null') && $default_value == "'null'") {
            $default_value = '';

        // Add the equal sign.
        } elseif (strlen($default_value)) {
            $default_value = '='.$default_value;
        }
    }

    return $default_value;
}

/**
 * API Call parameters.
 *
 * @param array $method_settings
 *
 * @return string
 */
function api_call_parameters($method_settings)
{
    $result = '';

    $options = [];

    // Search returns a model as the result.
    if (array_has($method_settings, 'auto-fill')) {
        if (is_string(array_get($method_settings, 'auto-fill'))) {
            $options[] = "'auto-fill' => '".array_get($method_settings, 'auto-fill')."'";
        } else {
            $options[] = "'auto-fill' => true";
        }
    }

    if (count($options)) {
        $result = ', [], ['.implode(', ', $options).']';
    }

    return $result;
}

/**
 * API Search parameters.
 *
 * @param array $method_settings
 *
 * @return string
 */
function api_search_parameters($method_settings)
{
    $result = '';

    // Search returns a model as the result.
    if (array_has($method_settings, 'factory')) {
        return ', '.factory_parameters($method_settings);
    }

    return $result;
}

/**
 * Generate a parameter list for creating a new class.
 *
 * @param array $method_settings
 *
 * @return string
 */
function generate_new_class_parameter_list($method_settings)
{
    $result = [];

    foreach (array_get($method_settings, 'parameters', []) as $name => $settings) {
        if (array_get($settings, 'required', false)) {
            $result[] = '$'.(array_get($settings, 'self', false) ? 'this->' : '')."$name";
        } else {
            $result[] = "\$$name";
        }
    }

    return implode(', ', $result);
}

/**
 * Generate the endpoint entry. This is simply for the correct code style.
 *
 * @param array $method_settings
 *
 * @return string
 */
function generate_endpoint_entry($method_settings)
{
    $entry = array_get($method_settings, 'endpoint', '');

    if (stripos($entry, '$') !== false) {
        return '"$entry"';
    }

    return "'$entry'";
}

/**
 * Generate the payload for the put function.
 *
 * @param array $method_settings
 *
 * @return string
 */
function generate_put_function_payload($method_settings)
{
    // Uses the attributes array but also takes
    // the optional array to update the attributes
    // before checking the dirty ones.
    if (array_has($method_settings, 'attributes')) {
        return '[\'json\' => $this->getDirty($optional)]';
    }

    $result = '';

    $entries = [];

    foreach (array_get($method_settings, 'parameters', []) as $name => $settings) {
        if (array_get($settings, 'required', false)) {
            $entries[] = ["            '".$name."'", "=> \$$name,"];
        }
    }

    if (count($entries)) {
        [$part1_length, $part2_length, $result] = code_alignment($entries);

        if (array_has($method_settings, 'optional', [])) {
            $optional = array_get($method_settings, 'optional', []);

            return "['json' => array_merge([\n".$result.'        ], $optional)]';
        }
    } elseif (array_has($method_settings, 'optional', [])) {
        return '[\'json\' => $optional]';
    }

    return "['json' => [\n".$result.'        ]]';
}

/**
 * Generate the payload for the post function.
 *
 * @param array $method_settings
 *
 * @return string
 */
function generate_post_function_payload($method_settings)
{
    $entries = [];
    $optional_count = 0;

    foreach (array_get($method_settings, 'parameters', []) as $name => $settings) {
        if (array_get($settings, 'required', false)) {
            $entries[] = ["            '".$name."'", "=> \$$name,"];
        } else {
            $optional_count++;
        }
    }

    [$part1_length, $part2_length, $result] = code_alignment($entries);

    if ($optional_count > 0) {
        $result = "array_merge([\n".$result.'        ], $optional)';
    } else {
        $result = "[\n".$result.'        ]';
    }

    if (count($entries) == 0 && $optional_count == 0) {
        return '';
    }

    return ", ['json' => ".$result.']';
}

/**
 * Factory parameters.
 *
 * @param array $method_settings
 *
 * @return string
 */
function factory_parameters($method_settings)
{
    $result = '';

    // Search returns a model as the result.
    if (array_has($method_settings, 'factory')) {
        $class = array_get($method_settings, 'factory.class');
        $parameters = implode("', '", array_get($method_settings, 'factory.parameters', []));

        return "['class' => '$class', 'parameters' => ['$parameters']]";
    }

    return $result;
}

/**
 * Generate list.
 *
 * @param array $data
 *
 * @return array
 */
function generate_list($data)
{
    $result = '';
    $max_length = 0;

    foreach ($data as $key => $value) {
        if (($length = strlen($key)) > $max_length) {
            $max_length = $length;
        }
    }

    foreach ($data as $key => $value) {
        $result .= "        '$key'".str_repeat(' ', $max_length - strlen($key))." => '$value',\n";
    }

    return $result;
}

/**
 * Align provided code.
 *
 * @param array $data
 *
 * @return array
 */
function code_alignment($data)
{
    $result = '';
    $part1_length = 0;
    $part2_length = 0;

    foreach ($data as $value) {
        if (($length = strlen($value[0])) > $part1_length) {
            $part1_length = $length;
        }

        if (isset($value[1])) {
            if (($length = strlen($value[1])) > $part2_length) {
                $part2_length = $length;
            }
        }
    }

    foreach ($data as $key => $value) {
        $total_part1 = $part1_length - strlen($value[0]);
        $total_part2 = $part2_length - strlen($value[1]);

        $total_part1 = $total_part1 < 0 ? 0 : $total_part1;
        $total_part2 = $total_part2 < 0 ? 0 : $total_part2;

        $result .= $value[0];
        $result .= str_repeat(' ', $total_part1).' ';
        $result .= $value[1];
        if (!empty($value[2])) {
            $result .= ' '.str_repeat(' ', $total_part2).$value[2];
        }
        $result .= "\n";
    }

    return [
        $part1_length,
        $part2_length,
        $result,
    ];
}

/**
 * Standardizes variable types.
 *
 * @param string $type
 *
 * @return string
 */
function convert_paramater_type($type)
{
    switch ($type) {
        case 'integer':
            return 'int';
    }

    return $type;
}

/**
 * Load properties list.
 *
 * @param array  $spec
 * @param string $path
 *
 * @return array
 */
function load_spec_properties($spec, $path)
{
    $path = str_replace('/', '.', ltrim($path, '#/'));

    return array_get($spec, $path.'.properties', []);
}
