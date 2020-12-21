<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Installment;
use DateTime;
use DateInterval;

class SalesController extends Controller
{
    /**
     * Register a new sale
     * @return Response
     * @throws ReflectionException
     */
    public function store(Request $request)
    {
        // Valida se o arquivo foi enviado
        $this->validate($request, ['file' => 'required']);
        // Arquivo enviado
        try {
            if(!$file = $request->file)
                throw new \Exception();
        } catch (\Exception $e) {
            return response()->json('Arquivo Não encontrado', 404);
        }
        $file = $request->file;
        // Define array da Venda
        $sales = array('sales' => array());
        // Montas array com os dados da Venda
        foreach(file($file) as $key => $line) {
            // ID da Venda
            $sales['sales'][$key]['id'] = substr($line, 1-1, 3);
            // Data da Venda
            $sales['sales'][$key]['date'] = date_format(date_create(substr($line, 4-1, 8)), 'Y-m-d');            
            // Valor da Venda
            $sales['sales'][$key]['amount'] = (float)substr_replace(substr($line, 12-1, 10), '.', -2, 0);
            // Busca os dados das parcelas
            $installments = array();
            $installments[$key] = $this->getInstallments($line);
            // Monta os dados das parcelas
            $sales['sales'][$key]['installments'] = $installments[$key];
            // Nome do Cliente
            $sales['sales'][$key]['customer']['name'] = trim(substr($line, 24-1, 20));
            // Busca os dados do endereço
            $address = $this->getAddress($line);
            // Monta os dados do endereço
            $sales['sales'][$key]['customer']['address'] = $address;

            /* Insere dados no BD - Desativado por padrão */
            // Insere os dados das Venda no DB
            // $this->insertSale($sales['sales'][$key]); // Desativado por padrão
            // Insere os dados das Parcelas no DB
            // $this->insertInstallments($sales['sales'][$key]['id'], $installments[$key]); // Desativado por padrão
            // Retorna arquivo json com os dados da Venda
        }
        return response()
            ->json(
                $sales
            );
    }

    private function getInstallments($line) : array 
    {
        $installments = array();
        // Número de Parcelas
        $numInstallments = (int)substr($line, 22-1, 2);
        // Valor de cada Parcela
        $amount = (float)substr_replace(substr($line, 12-1, 10), '.', -2, 0);
        // Valor das parcelas
        $amountPart = (float)bcdiv($amount, $numInstallments, 2);
        // Diferença a ser adicionada na primeira parcela
        $diff = $amount-($amountPart*$numInstallments); 
        for ($i = 0; $i < $numInstallments; $i++) {
            // Data da Venda
            $date = new DateTime(substr($line, 4-1, 8));
            // Número da parcela
            $numParc = $i+1;
            // Número de dias das parcelas
            $sMeses = $numParc*30;
            // Add +30 a data original da Venda
            $prevDate = $date->add(new DateInterval("P{$sMeses}D"));
            // Formata a data 
            $datePart = date_format($prevDate, 'Y-m-d');
            // Busca o dia da semana da data
            $dayWeek = date('w', strtotime($datePart));

            if($dayWeek==0){ // Se cair no Domingo add 1 dia (segunda)
                // Add 1 dia a Data
                $datePart =  $date->add(new DateInterval("P1D"));
                // Formata a data
                $datePart = date_format($prevDate, 'Y-m-d');
            } else if($dayWeek==6) { // Se cair no sábado add 2 dias (segunda)
                // Add 2 dias a Data
                $datePart =  $date->add(new DateInterval("P2D"));
                // Formata a data
                $datePart = date_format($prevDate, 'Y-m-d');
            }
            // Monta array com os dados da parcela
            $installments[$i]['installment'] = $i+1;
            $installments[$i]['amount'] = (float)($diff>0 ? ($amountPart+$diff) : $amountPart);
            $installments[$i]['date'] = $datePart;
        }
        // Retorna os dados das Parcelas
        return $installments;
    }

    private function getAddress($line) : array 
    {
        $address = array();
        // get CEP
        $cep = (string)substr($line, 44-1, 8);

        // Tentei com a class GuzzleHttp, mas sempre retornou erro 500 Internal Server Error

        // URL
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        // CURL
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);
        // array com o resulta da busca da API dos Correios
        $result = (array) json_decode($result);
        // Monta array com os dados do endereço
        $address['street'] = (isset($result['logradouro']) ? $result['logradouro'] : '');
        $address['neighborhood'] = (isset($result['logradouro']) ? $result['bairro'] : '');
        $address['city'] = (isset($result['logradouro']) ? $result['localidade'] : '');
        $address['state'] = (isset($result['logradouro']) ? $result['uf'] : '');
        $address['postal_code'] = vsprintf("%s%s%s%s%s-%s%s%s", str_split($cep));
        // Retorna os Dados do Endereço
        return $address;
    }

    private function insertSale($sale) :bool 
    {
        $data = array();
        // ID da Venda
        $data['id'] = $sale['id'];
        // Data da Venda
        $data['date'] = str_replace('-', '', $sale['date']);            
        // Valor da Venda
        $data['amount'] = $sale['amount'];
        // Nome do Cliente
        $data['customer'] = $sale['customer']['name'];
        // Nome do Cliente
        $data['street'] = $sale['customer']['address']['street'];
        // Nome do Cliente
        $data['neighborhood'] = $sale['customer']['address']['neighborhood'];
        // Nome do Cliente
        $data['city'] = $sale['customer']['address']['city'];
        // Nome do Cliente
        $data['state'] = $sale['customer']['address']['state'];
        // Nome do Cliente
        $data['postal_code'] = str_replace('-', '', $sale['customer']['address']['postal_code']);
        // Monta os dados das parcelas
        $data['installments'] = count($sale['installments']);
        // Insere e retorna insert do DB
        try {
            if(!Sale::create($data))
                throw new \Exception();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    private function insertInstallments($sale_id, $installments) :bool 
    {
        foreach($installments as $installment) {
            // Número da Parcela
            $data['installment'] = $installment['installment'];
            // Valor da Parcela
            $data['amount'] = $installment['amount'];            
            // Data da Parcela
            $data['date'] = str_replace('-', '', $installment['date']);          
            // ID da Venda
            $data['sale_id'] = $sale_id;
            // Insere e retorna insert do DB
            try {
                if(!Installment::create($data))
                    throw new \Exception();
            } catch (\Exception $e) {
                //echo response()->json('Não autorizado', 401);
                return false;
            }
        }     
        return true;
    }
}