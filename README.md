# Mikrotik RouterOS - PHP | API

Uma biblioteca para facilitar, desenvolvida para trabalhar com `PHP7` e `PHP8`.

Você pode usar esta biblioteca com versões 6.43+ do firmware RouterOS.

Instalação:

```shell
composer require joserf/mikrotik-php-api
```
## Índice

* [Requisitos mínimos](#requisitos-m%C3%ADnimos)
* [Como Usar](#como-usar)
* [Configurando Mikrotik](#configurando-mikrotik)
* [Exemplos de uso](#exemplos-de-uso)
  * [Monitorando tráfego na interface](#monitorando-tr%C3%A1fego-na-interface)
  * [Total de usuários on-line (hotspot)](#total-de-usu%C3%A1rios-on-line-hotspot)
  * [Usuários on-line (hotspot)](#usu%C3%A1rios-on-line-hotspot-json)
* [Links](#Links)

## Requisitos mínimos

* `php` >= 7.2|8.0
* `ext-sockets`


## Como usar

Alterar os campos conforme exemplo abaixo, alterando o IP, login e senha.

```php
$config =
    (new Config())
        ->set('host', '192.168.*.*')
        ->set('port', 8728) 
        ->set('user', 'LOGIN')
        ->set('pass', 'SENHA');
```
## Configurando Mikrotik

### Unable to establish socket session, Connection refused.

Este erro significa que a biblioteca não pode se conectar ao seu roteador,
que o Mikrotik está desligado ou o serviço da API não está ativo.

Vá para `IP -> Services` e habilitar `api`.

Ou via linha de comando:

```shell script
/ip service enable api 
```
Deseja mais segurança?

```shell script
 /ip service set api address=192.168.*.*
```
### Exemplos de uso

> Arquivo `exemplo.php` completo.

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

// Create config object with parameters
$config =
    (new Config())
        ->set('host', '192.168.*.*')
        ->set('user', 'LOGIN')
        ->set('pass', 'SENHA');

// Initiate client with config object
$client = new Client($config);

// Build query (Get resources in RouterOS)
$query = new Query("/system/resource/print");

// Send query to RouterOS
$request = $client->query($query);

// Read answer from RouterOS
$response = $client->read();

// Print result in json format
print_r(json_encode($response, JSON_PRETTY_PRINT));

?>
```
Após a execução do comando `php exemplo.php`, teremos como resultado:

```shell script
[
    {
        "uptime": "5h34m8s",
        "version": "6.47.9 (long-term)",
        "build-time": "Feb\/08\/2021 12:48:33",
        "free-memory": "1037193216",
        "total-memory": "1073741824",
        "cpu": "Intel(R)",
        "cpu-count": "1",
        "cpu-frequency": "3791",
        "cpu-load": "0",
        "free-hdd-space": "8401121280",
        "total-hdd-space": "8490053632",
        "write-sect-since-reboot": "6296",
        "write-sect-total": "6296",
        "architecture-name": "x86",
        "board-name": "x86",
        "platform": "MikroTik"
    }
]
```
> *Podemos efetuar o teste via web: `php -S localhost:8000/exemplo.php`

## Monitorando tráfego na interface
> comentário: `ether1`

```php
// Build monitoring query (/interface monitor-traffic interface=ether1)
$query =
    (new Query('/interface/monitor-traffic'))
        ->equal('interface', 'ether1')
        ->equal('once');
        
// Monitoring details
$out = $client->query($query)->read();
print_r($out);        
        
```
Podemos exibir apenas `rx-bits-per-second`
```php
// show only rx-bits-per-second
print_r($out [0]["rx-bits-per-second"]);
```

## Total de usuários on-line (hotspot)
```php
// Build query (Get users active in RouterOS)
$query = new Query("/ip/hotspot/active/print");

// Count total user active in RouterOS
print_r(count($response));
```
## Usuários on-line (hotspot) JSON
```php
// Build query (Get users active in RouterOS)
$query = new Query("/ip/hotspot/active/print");

// Print result in JSON format
print_r(json_encode($response, JSON_PRETTY_PRINT));

```

## Uptime
```php
// Build query (Get resources in RouterOS)
$query = new Query("/system/resource/print");

// Show uptime active in RouterOS
echo 'Uptime: ' . json_encode($response[0]['uptime']);

```


## Links

* [RouterOS Manual:API](https://wiki.mikrotik.com/wiki/Manual:API) - Mikrotik oficial




