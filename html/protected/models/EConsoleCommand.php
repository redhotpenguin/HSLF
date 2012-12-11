<?php
/**
 * ConsoleCommandEx class file.
 *
 * @author Damián Nohales <damiannohales@gmail.com
 * @copyright Copyright (c) 2011 Damián Nohales
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
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * ConsoleCommandEx implements some functionality to make Yii console commands
 * more interactive and user friendly.
 * @author Damián Nohales <damiannohales@gmail.com
 * @version 1.0
 */
class EConsoleCommand extends CConsoleCommand
{
	//Foreground colors
	const FG_BLACK   = '0;30';
	const FG_RED     = '0;31';
	const FG_GREEN   = '0;32';
	const FG_YELLOW  = '0;33';
	const FG_BLUE    = '0;34';
	const FG_MAGENTA = '0;35';
	const FG_CYAN    = '0;36';
	const FG_WHITE   = '0;37';
	
	//Bold foreground colors
	const FGB_BLACK   = '1;30';
	const FGB_RED     = '1;31';
	const FGB_GREEN   = '1;32';
	const FGB_YELLOW  = '1;33';
	const FGB_BLUE    = '1;34';
	const FGB_MAGENTA = '1;35';
	const FGB_CYAN    = '1;36';
	const FGB_WHITE   = '1;37';
	
	//Background colors
	const BG_BLACK   = '40';
	const BG_RED     = '41';
	const BG_GREEN   = '42';
	const BG_YELLOW  = '43';
	const BG_BLUE    = '44';
	const BG_MAGENTA = '45';
	const BG_CYAN    = '46';
	const BG_WHITE   = '47';
	
	/**
	 * @var bool If is False the colors are omitted. The value is determined by
	 * the characteristics of the terminal, if is a tty UNIX terminal (eg. BASH
	 * with not piped STDOUT), is setted to true, otherwise is setted to false.
	 */
	protected $shouldUseColors;
	
	/**
	 * Determine if the command should use colors, if you overwrite this method
	 * remember to return the parent value.
	 * @param string $action the action name
	 * @param array $params the parameters to be passed to the action method.
	 * @return bool whether the action should be executed.
	 */
	protected function beforeAction($action, $params)
	{
		$this->shouldUseColors = true;
		
		if( function_exists('posix_isatty') ){
			$this->shouldUseColors = posix_isatty(STDOUT);
		}
		
		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
			$this->shouldUseColors = false;
		}
		
		return parent::beforeAction($action, $params);
	}
	
	/**
	 * Force to disable the output colors.
	 */
	public function disableColors()
	{
		$this->shouldUseColors = false;
	}
	
	/**
	 * Force to enable the output colors.
	 */
	public function enableColors()
	{
		$this->shouldUseColors = true;
	}
	
	/**
	 * Prompt a string to the user.
	 * You can use an optional message to show at prompt: "My message: ".
	 * When use de $default argument and the user introduce an empty input, this
	 * parameter is returned, additionally, if you use in combination with
	 * $message, the prompt string shows as the following example:
	 * "My message [default value]: ".
	 * @param string $message an optional message to show at string prompting.
	 * @param string $default a default value to use when user introduces an
	 * empty entry
	 * @param bool $trimmed if the entry should be treated trimmed. The input is
	 * ever treated without the trailing new line character.
	 * @return string the user input or $default if user introduces nothing.
	 */
	public function promptString($message = null, $default = null, $trimmed = true)
	{
		if($message !== null){
			echo $message;
			if( $default !== null ){
				echo " [$default]";
			}
			echo ': ';
		}
		$input = fgets(STDIN);
		$input = substr($input, 0, -1);
		if($trimmed){
			$input = trim($input);
		}
		return empty($input) && $default !== null? $default:$input;
	}

	/**
	 * Prompt an integer number to the user.
	 * @param string $message an optional message to show at string prompting.
	 * @param int $default a default value to use when user introduces an
	 * empty entry
	 * @return int the number introduced or the default value.
	 */
	public function promptNumber($message = null, $default = null)
	{
		return (int)$this->promptString($message, $default);
	}
	
	/**
	 * Prompt a float number to the user.
	 * @param string $message an optional message to show at string prompting.
	 * @param float $default a default value to use when user introduces an
	 * empty entry
	 * @return float the number introduced or the default value.
	 */
	public function promptFloat($message = null, $default = null)
	{
		return (float)$this->promptString($message, $default);
	}
	
	/**
	 * Prompt user by Yes or No
	 * @param string $message an optional message to show at prompting.
	 * @param bool $printYesNo If is true shows " [yes|no] " at prompting
	 * @return bool True if user respond Yes, otherwise, return False
	 */
	public function confirm($message = null, $printYesNo = true)
	{
		if($message !== null){
			echo $message;
		}
		if($printYesNo){
			echo ' [yes|no] ';
		}
		return !strncasecmp(trim(fgets(STDIN)),'y',1);
	}
	
	/**
	 * Print a text on screen with various options.
	 * @param string $text the text to show.
	 * @param bool $newline if is True, print a new line at the end of text.
	 * @param string $fg a foreground color or null to not colorize foreground.
	 * @param string $bg a background color or null to not colorize background.
	 */
	protected function printInternal($text, $newline = false, $fg = null, $bg = null)
	{
		if(!$this->shouldUseColors){
			$bg = $fg = null;
		}
		
		if($bg) echo chr(27)."[{$bg}m";
		if($fg) echo chr(27)."[{$fg}m";
		echo $text;
		if($fg || $bg) echo chr(27)."[0m";
		if($newline) echo "\n";
	}
	
	/**
	 * Print a text with a new line at the end.
	 * @param string $text the text to show.
	 */
	public function println($text){
		$this->printInternal($text, true);
	}
	
	/**
	 * Print a colorized text.
	 * @param string $text the text to show.
	 * @param string $fg a foreground color or null to not colorize foreground.
	 * @param string $bg a background color or null to not colorize background.
	 */
	public function printColor($text, $fg = null, $bg = null){
		$this->printInternal($text, false, $fg, $bg);
	}
	
	/**
	 * Print a colorized text with a new line at the end.
	 * @param string $text the text to show.
	 * @param string $fg a foreground color or null to not colorize foreground.
	 * @param string $bg a background color or null to not colorize background.
	 */
	public function printlnColor($text, $fg = null, $bg = null){
		$this->printInternal($text, true, $fg, $bg);
	}
	
	/**
	 * Print an error text (red).
	 * @param string $text the text to show.
	 */
	public function printError($text){
		$this->printColor($text, self::FGB_RED);
	}
	
	/**
	 * Print an error text (red) with a new line at the end.
	 * @param string $text the text to show.
	 */
	public function printlnError($text){
		$this->printlnColor($text, self::FGB_RED);
	}
	
	/**
	 * Print a success text (green).
	 * @param string $text the text to show.
	 */
	public function printSuccess($text){
		$this->printColor($text, self::FGB_GREEN);
	}
	
	/**
	 * Print a success text (green) with a new line at the end.
	 * @param string $text the text to show.
	 */
	public function printlnSuccess($text){
		$this->printlnColor($text, self::FGB_GREEN);
	}
	
	/**
	 * Print a notice text (yellow).
	 * @param string $text the text to show.
	 */
	public function printNotice($text){
		$this->printColor($text, self::FGB_YELLOW);
	}
	
	/**
	 * Print a notice text (yellow) with a new line at the end.
	 * @param string $text the text to show.
	 */
	public function printlnNotice($text){
		$this->printlnColor($text, self::FGB_YELLOW);
	}
}

?>
