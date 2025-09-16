<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\RequestException;
use SimpleXMLElement;

class OracleBIService
{
    protected string $endpoint;
    protected string $user;
    protected string $password;
    protected string $payloadTemplate;

    public function __construct()
    {
        $this->endpoint = config('services.oracle_bi.endpoint');
        $this->user = config('services.oracle_bi.user');
        $this->password = config('services.oracle_bi.password');

        $payload = Storage::disk('local')->get('payload.xml');

        if (!$payload) {
            throw new \Exception('payload.xml not found in your local storage disk root (storage/app/private).');
        }

        $this->payloadTemplate = $payload;
    }

    /**
     * Run a report on the Oracle BI server.
     *
     * @param string $reportPath The absolute path of the report on the server.
     * @param array $parameters An associative array of parameters for the report.
     * @param string $attributeFormat The desired format of the report attributes.
     * @param string $attributeLocale The desired locale for the report attributes.
     * @return string The decoded CSV data from the report.
     * @throws \Exception
     */
    public function runReport(string $reportPath, array $parameters = [], string $attributeFormat = 'csv', string $attributeLocale = 'en-US'): string
    {
        $xmlPayload = $this->buildPayload($reportPath, $parameters, $attributeFormat, $attributeLocale);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml;charset=UTF-8',
                'SOAPAction' => 'runReport'
            ])->send('POST', $this->endpoint, [
                'body' => $xmlPayload
            ]);

            $response->throw(); // Throw an exception for 4xx/5xx responses

            return $this->parseResponse($response->body());

        } catch (RequestException $e) {
            // Handle connection errors or HTTP errors
            throw new \Exception("Failed to connect to Oracle BI Service: " . $e->getMessage());
        }
    }

    /**
     * Build the XML payload for the SOAP request.
     */
    protected function buildPayload(string $reportPath, array $parameters, string $attributeFormat, string $attributeLocale): string
    {
        $payload = $this->payloadTemplate;

        // Basic placeholders
        $payload = str_replace('{{ATTRIBUTE_FORMAT}}', $attributeFormat, $payload);
        $payload = str_replace('{{ATTRIBUTE_LOCALE}}', $attributeLocale, $payload);
        $payload = str_replace('{{ABSOLUTE_PATH}}', $reportPath, $payload);
        $payload = str_replace('{{DOWNLOAD_SIZE}}', '-1', $payload); // Placeholder size
        $payload = str_replace('{{USER_ID}}', $this->user, $payload);
        $payload = str_replace('{{PASSWORD}}', $this->password, $payload);

        // Build parameter XML
        $parameterXml = '';
        foreach ($parameters as $name => $value) {
            $parameterXml .= '<pub:item><pub:name>' . htmlspecialchars($name) . '</pub:name><pub:values><pub:item>' . htmlspecialchars($value) . '</pub:item></pub:values></pub:item>';
        }

        // Replace the parameter block
        $payload = preg_replace('/<pub:parameterNameValues>.*<\/pub:parameterNameValues>/s', '<pub:parameterNameValues>' . $parameterXml . '</pub:parameterNameValues>', $payload);

        return $payload;
    }

    /**
     * Parse the SOAP response to extract and decode the report data.
     */
    protected function parseResponse(string $responseBody): string
    {
        // Use SimpleXMLElement to parse the SOAP response
        $xml = new SimpleXMLElement($responseBody);

        // Register the SOAP and public report service namespaces
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('pub', 'http://xmlns.oracle.com/oxp/service/PublicReportService');

        // Find the reportBytes element
        $reportBytesNode = $xml->xpath('//pub:reportBytes');

        if (empty($reportBytesNode)) {
            // Handle cases where the report data is not found
            throw new \Exception("Report data not found in Oracle BI response.");
        }

        // Decode the Base64 CSV data
        $base64Data = (string) $reportBytesNode[0];
        $decodedCsv = base64_decode($base64Data);

        if ($decodedCsv === false) {
            throw new \Exception("Failed to decode Base64 report data.");
        }

        return $decodedCsv;
    }
}
