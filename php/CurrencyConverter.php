<?php
/**
 * This class converts between currencies.
 * Uses bcmath (PHP with --enable-bcmath).
 * Default calculation precision is set to four digits.
 *
 * @url https://github.com/mailopl/code-example/tree/master/php/CurrencyConverter.php
 * @author Marcin Wawrzyniak
 */
class Converter
{
    /**
     * URL to fetch data
     **/
    protected $url = "http://www.nbp.pl/kursy/kursya.html";

    /**
     * Currencies data.
     * Contains keys: name, course, scaler, code
     **/
    protected $data = array();

    /**
     * Conversion precision
     */
    protected $precision = 4;

    /**
     * This method fetches data from remote website using DOM and XPath,
     * and populates $this->data.
     */
    public function __construct()
    {
        $html = file_get_contents($this->url); // normally would use some Requests library

        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new DOMXPath($dom);
        $rows = $xpath->query('/html/body/table[2]/tr/td/center/table[1]/tr'); // Xpath request, hardcoded

        foreach ($rows as $row) {

            if (!$xpath->query('td[1]', $row)->item(0)) {
                continue;
            }

            list($scaler, $code) = explode(" ", $xpath->query('td[2]', $row)->item(0)->nodeValue);

            $this->data[] = array(
                'name' => $xpath->query('td[1]', $row)->item(0)->nodeValue,
                'course' => str_replace(",", ".", $xpath->query('td[3]', $row)->item(0)->nodeValue),
                'scaler' => intval($scaler),
                'code' => trim($code)
            );
        }
    }

    /**
     * Checks if symbol exists in fetched data
     *
     * @param $code     Currency code, default EUR
     * @return boolean
     */
    protected function _symbolExists($code = 'EUR')
    {
        return (boolean)array_filter(
            $this->data,
            function ($var) use ($code) {
                return $var['code'] == $code;
            }
        );
    }

    /**
     * Returns currency object
     *
     * @param $code         Currency code, default EUR
     * @return stdObject    Element of $this->data
     */
    protected function _getCurrencyObject($code = 'EUR')
    {
        $item = array_filter(
            $this->data,
            function ($var) use ($code) {
                return $var['code'] == $code;
            }
        );

        return (object)array_shift($item);
    }

    /**
     * Does conversion to PLN
     *
     * @param $kwota_waluty   Currency value
     * @param $symbol_waluty  Currency symbol, default EUR
     *
     * @throws BadMethodCallException If currency symbol is not found
     */
    public function convertToPLN($kwota_waluty, $symbol_waluty = 'EUR')
    {
        if (!$this->_symbolExists($symbol_waluty)) {
            throw new BadMethodCallException("symbol_waluty");
        }

        $currency = $this->_getCurrencyObject($symbol_waluty);

        return bcdiv(
            bcmul($kwota_waluty, $currency->course, $this->precision),
            $currency->scaler,
            $this->precision
        );

    }

    /**
     * Does conversion from PLN to currency specified in second argument
     *
     * @param $kwota_zlotych   PLN value
     * @param $symbol_waluty   Currency symbol, default EUR
     *
     * @throws BadMethodCallException If currency symbol is not found
     */
    public function convertToCurrency($kwota_zlotych, $symbol_waluty = 'EUR')
    {
        if (!$this->_symbolExists($symbol_waluty)) {
            throw new BadMethodCallException("symbol_waluty");
        }

        $currency = $this->_getCurrencyObject($symbol_waluty);

        return bcdiv(
            bcmul($currency->scaler, $kwota_zlotych, $this->precision),
            $currency->course,
            $this->precision
        );

    }

}

;

$converter = new Converter;
var_dump($converter->convertToPLN(2500, "JPY"));
var_dump($converter->convertToCurrency(150, 'CAD'));