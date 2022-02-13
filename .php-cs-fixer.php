<?php

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2' => true,
    // Each line of multi-line DocComments must have an asterisk [PSR-5] and must be aligned with the first one.
    // 'align_multiline_comment' => true,
    // Each element of an array must be indented exactly once.
    'array_indentation' => true,
    // PHP arrays should be declared using the configured syntax.
    'array_syntax' => ['syntax' => 'short'],
    // Binary operators should be surrounded by space as configured.
    'binary_operator_spaces' => true,
    // Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.
    'blank_line_after_opening_tag' => true,
    // An empty line feed must precede any configured statement.
    'blank_line_before_statement' => true,
    // A single space or none should be between cast and variable.
    'cast_spaces' => true,
    // Class, trait and interface elements must be separated with one blank line.
    'class_attributes_separation' => true,
    // Remove extra spaces in a nullable typehint.
    'compact_nullable_typehint' => true,
    // Concatenation should be spaced according configuration.
    'concat_space' => ['spacing' => 'one'],
    // Add curly braces to indirect variables to make them clear to understand.
    // Requires PHP >= 7.0.
    'explicit_indirect_variable' => true,
    // Converts implicit variables into explicit ones in double-quoted strings or heredoc syntax.
    'explicit_string_variable' => true,
    // Transforms imported FQCN parameters and return types in function arguments to short version.
    'fully_qualified_strict_types' => true,
    // Add missing space between function's argument and its typehint.
    'function_typehint_space' => true,
    // Pre- or post-increment and decrement operators should be used if possible.
    'increment_style' => ['style' => 'post'],
    // Ensure there is no code on the same line as the PHP open tag.
    'linebreak_after_opening_tag' => true,
    // Cast should be written in lower case.
    'lowercase_cast' => true,
    // Class static references `self`, `static` and `parent` MUST be in lower case.
    'lowercase_static_reference' => true,
    // Magic constants should be referred to using the correct casing.
    'magic_constant_casing' => true,
    // Magic method definitions and calls must be using the correct casing.
    'magic_method_casing' => true,
    // Method chaining MUST be properly indented.
    // Method chaining with different levels of indentation is not supported.
    'method_chaining_indentation' => true,
    // DocBlocks must start with two asterisks, multiline comments must start with a single asterisk, after the opening slash.
    // Both must end with a single asterisk before the closing slash.
    'multiline_comment_opening_closing' => true,
    // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
    'multiline_whitespace_before_semicolons' => true,
    // Function defined by PHP should be called using the correct casing.
    'native_function_casing' => true,
    // All instances created with new keyword must be followed by braces.
    'new_with_braces' => true,
    // Replace control structure alternative syntax to use braces.
    'no_alternative_syntax' => true,
    // There should be no empty lines after class opening brace.
    'no_blank_lines_after_class_opening' => true,
    // There should not be blank lines between docblock and the documented element.
    'no_blank_lines_after_phpdoc' => true,
    // There should not be empty PHPDoc blocks.
    'no_empty_phpdoc' => true,
    // Remove useless semicolon statements.
    'no_empty_statement' => true,
    // Removes extra blank lines and/or blank lines following configuration.
    'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'curly_brace_block', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'throw', 'use', 'use_trait', 'switch', 'case', 'default']],
    // Remove leading slashes in `use` clauses.
    'no_leading_import_slash' => true,
    // The namespace declaration line shouldn't contain leading whitespace.
    'no_leading_namespace_whitespace' => true,
    // Operator `=>` should not be surrounded by multi-line whitespaces.
    'no_multiline_whitespace_around_double_arrow' => true,
    // Short cast `bool` using double exclamation mark should not be used.
    'no_short_bool_cast' => true,
    // Single-line whitespace before closing semicolon are prohibited.
    'no_singleline_whitespace_before_semicolons' => true,
    // There MUST NOT be spaces around offset braces.
    'no_spaces_around_offset' => true,
    // Remove trailing commas in list function calls.
    'no_trailing_comma_in_list_call' => true,
    // PHP single-line arrays should not have trailing comma.
    'no_trailing_comma_in_singleline_array' => true,
    // Removes unneeded parentheses around control statements.
    'no_unneeded_control_parentheses' => true,
    // Unused `use` statements must be removed.
    'no_unused_imports' => true,
    // There should not be useless `else` cases.
    'no_useless_else' => true,
    // There should not be an empty `return` statement at the end of a function.
    'no_useless_return' => true,
    // In array declaration, there MUST NOT be a whitespace before each comma.
    'no_whitespace_before_comma_in_array' => true,
    // Remove trailing whitespace at the end of blank lines.
    'no_whitespace_in_blank_line' => true,
    // Array index should always be written by using square braces.
    'normalize_index_brace' => true,
    // Logical NOT operators (`!`) should have one trailing whitespace.
    'not_operator_with_successor_space' => true,
    // There should not be space before or after object `T_OBJECT_OPERATOR` `->`.
    'object_operator_without_whitespace' => true,
    // Ordering `use` statements.
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    // Enforce camel (or snake) case for PHPUnit test methods, following configuration.
    'php_unit_method_casing' => ['case' => 'snake_case'],
    // All items of the given phpdoc tags must be either left-aligned or (by default) aligned vertically.
    'phpdoc_align' => ['align' => 'left'],
    // PHPDoc annotation descriptions should not be a sentence.
    'phpdoc_annotation_without_dot' => true,
    // Docblocks should have the same indentation as the documented subject.
    'phpdoc_indent' => true,
    // Fix PHPDoc inline tags, make `@inheritdoc` always inline.
    // `@access` annotations should be omitted from PHPDoc.
    'phpdoc_no_access' => true,
    // No alias PHPDoc tags should be used.
    'phpdoc_no_alias_tag' => true,
    // `@package` and `@subpackage` annotations should be omitted from PHPDoc.
    'phpdoc_no_package' => true,
    // Classy that does not inherit must not have `@inheritdoc` tags.
    'phpdoc_no_useless_inheritdoc' => true,
    // Annotations in PHPDoc should be ordered so that `@param` annotations come first, then `@throws` annotations, then `@return` annotations.
    'phpdoc_order' => true,
    // The type of `@return` annotations of methods returning a reference to itself must the configured one.
    'phpdoc_return_self_reference' => true,
    // Scalar types should always be written in the same form.
    // `int` not `integer`, `bool` not `boolean`, `float` not `real` or `double`.
    'phpdoc_scalar' => true,
    // Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line.
    'phpdoc_separation' => true,
    // Single line `@var` PHPDoc should have proper spacing.
    'phpdoc_single_line_var_spacing' => true,
    // PHPDoc summary should end in either a full stop, exclamation mark, or question mark.
    'phpdoc_summary' => true,
    // Docblocks should only be used on structural elements.
    'phpdoc_to_comment' => true,
    // PHPDoc should start and end with content, excluding the very first and last line of the docblocks.
    'phpdoc_trim' => true,
    // Removes extra blank lines after summary and after description in PHPDoc.
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    // The correct case must be used for standard PHP types in PHPDoc.
    'phpdoc_types' => true,
    // Sorts PHPDoc types.
    'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
    // `@var` and `@type` annotations should not contain the variable name.
    'phpdoc_var_without_name' => true,
    // There should be one or no space before colon, and one space after it in return type declarations, according to configuration.
    'return_type_declaration' => ['space_before' => 'one'],
    // Instructions must be terminated with a semicolon.
    'semicolon_after_instruction' => true,
    // Cast `(boolean)` and `(integer)` should be written as `(bool)` and `(int)`, `(double)` and `(real)` as `(float)`, `(binary)` as `(string)`.
    'short_scalar_cast' => true,
    // There should be exactly one blank line before a namespace declaration.
    'single_blank_line_before_namespace' => true,
    // Convert double quotes to single quotes for simple strings.
    'single_quote' => ['strings_containing_single_quote_chars' => true],
    // Fix whitespace after a semicolon.
    'space_after_semicolon' => true,
    // Increment and decrement operators should be used if possible.
    'standardize_increment' => true,
    // Replace all `<>` with `!=`.
    'standardize_not_equals' => true,
    // Standardize spaces around ternary operator.
    'ternary_operator_spaces' => true,
    // Use `null` coalescing operator `??` where possible.
    // Requires PHP >= 7.0.
    'ternary_to_null_coalescing' => true,
    // PHP multi-line arrays should have a trailing comma.
    'trailing_comma_in_multiline' => true,
    // Arrays should be formatted like function/method arguments, without leading or trailing single line space.
    'trim_array_spaces' => true,
    // Unary operators should be placed adjacent to their operands.
    'unary_operator_spaces' => true,
    // In array declaration, there MUST be a whitespace after each comma.
    'whitespace_after_comma_in_array' => true,
    // Force Fully-Qualified Class Name in docblocks
    'AdamWojs/phpdoc_force_fqcn_fixer' => true,
];

$finder = Finder::create()
    ->notPath('bootstrap')
    ->notPath('storage')
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->name('_ide_helper')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->registerCustomFixers([
        new ForceFQCNFixer(),
    ])
    ->setRules($rules)
    ->setFinder($finder);
