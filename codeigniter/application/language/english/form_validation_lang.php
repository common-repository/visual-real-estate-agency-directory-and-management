<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['form_validation_required']		= __('The {field} field is required.', 'sw_win');
$lang['form_validation_isset']			= __('The {field} field must have a value.', 'sw_win');
$lang['form_validation_valid_email']		= __('The {field} field must contain a valid email address.', 'sw_win');
$lang['form_validation_valid_emails']		= __('The {field} field must contain all valid email addresses.', 'sw_win');
$lang['form_validation_valid_url']		= __('The {field} field must contain a valid URL.', 'sw_win');
$lang['form_validation_valid_ip']		= __('The {field} field must contain a valid IP.', 'sw_win');
$lang['form_validation_min_length']		= __('The {field} field must be at least {param} characters in length.', 'sw_win');
$lang['form_validation_max_length']		= __('The {field} field cannot exceed {param} characters in length.', 'sw_win');
$lang['form_validation_exact_length']		= __('The {field} field must be exactly {param} characters in length.', 'sw_win');
$lang['form_validation_alpha']			= __('The {field} field may only contain alphabetical characters.', 'sw_win');
$lang['form_validation_alpha_numeric']		= __('The {field} field may only contain alpha-numeric characters.', 'sw_win');
$lang['form_validation_alpha_numeric_spaces']	= __('The {field} field may only contain alpha-numeric characters and spaces.', 'sw_win');
$lang['form_validation_alpha_dash']		= __('The {field} field may only contain alpha-numeric characters, underscores, and dashes.', 'sw_win');
$lang['form_validation_numeric']		= __('The {field} field must contain only numbers.', 'sw_win');
$lang['form_validation_is_numeric']		= __('The {field} field must contain only numeric characters.', 'sw_win');
$lang['form_validation_integer']		= __('The {field} field must contain an integer.', 'sw_win');
$lang['form_validation_regex_match']		= __('The {field} field is not in the correct format.', 'sw_win');
$lang['form_validation_matches']		= __('The {field} field does not match the {param} field.', 'sw_win');
$lang['form_validation_differs']		= __('The {field} field must differ from the {param} field.', 'sw_win');
$lang['form_validation_is_unique'] 		= __('The {field} field must contain a unique value.', 'sw_win');
$lang['form_validation_is_natural']		= __('The {field} field must only contain digits.', 'sw_win');
$lang['form_validation_is_natural_no_zero']	= __('The {field} field must only contain digits and must be greater than zero.', 'sw_win');
$lang['form_validation_decimal']		= __('The {field} field must contain a decimal number.', 'sw_win');
$lang['form_validation_less_than']		= __('The {field} field must contain a number less than {param}.', 'sw_win');
$lang['form_validation_less_than_equal_to']	= __('The {field} field must contain a number less than or equal to {param}.', 'sw_win');
$lang['form_validation_greater_than']		= __('The {field} field must contain a number greater than {param}.', 'sw_win');
$lang['form_validation_greater_than_equal_to']	= __('The {field} field must contain a number greater than or equal to {param}.', 'sw_win');
$lang['form_validation_error_message_not_set']	= __('Unable to access an error message corresponding to your field name {field}.', 'sw_win');
$lang['form_validation_in_list']		= __('The {field} field must be one of: {param}.', 'sw_win');
