// biblioteca para enviar as requisicoes http
#include <ESP8266HTTPClient.h>

// bibliotecas para conectar o esp8266 ao wi-fi
#include <ESP8266WiFi.h>
#include <WiFiClient.h>

// bibliotecas para ler a temperatura
#include <DallasTemperature.h>
#include <OneWire.h>

// biblioteca para enviar notificação para o bot do telegram
#include <CTBot.h>

//Instancia os objetos
#define barramento 2 //Pino de sinal (amarelo) do DS18b20 ligado ao pino 2
OneWire oneWire(barramento);
DallasTemperature ds18b20(&oneWire);
WiFiClient client; // cliente responsável pela conexao
CTBot myBot;

//Declaracao das variaveis
const char* ssid = "MiMi"; 
const char* senha = "113126virus";
char servidor[] = "http://192.168.88.229/tcc2/enviaDados.php";
String BOT_TOKEN = "2099943113:AAFIjD92tu3qia0v9wEQcQ2wt5xnEpMsy_w"; // token do telegram
const int CHAT_ID = 789976451; // id do telegram

void setup() 
{
  Serial.begin(115200);

  // conexão do microcontrolador ao wi-fi
  WiFi.begin(ssid, senha);
  //permanece neste laço até a conexão
  while (WiFi.status() != WL_CONNECTED) 
  {
    delay(500); 
    Serial.print("."); 
  }
  ds18b20.begin(); // inicializa o sensor

  // inicializacao da biblioteca para o telegram
  Serial.println("Inicializando TelegramBot...");

  // insere usuario e senha do wi-fi
  myBot.wifiConnect(ssid, senha);

  // insere o token do telegram
  myBot.setTelegramToken(BOT_TOKEN);
  
  // checa se a conexão está ok
  if (myBot.testConnection())
    Serial.println("\nConexao ON");
  else
    Serial.println("\nConexao OFF");
}

void loop() 
{
  Enviar(); 
  //espera
  delay(90000);
}

void Enviar()
{
    // media de temperatura
    double leitura = 0, media = 0;
    for (int i = 0; i < 10; i++)
    {
        ds18b20.requestTemperatures();
        leitura += ds18b20.getTempCByIndex(0);
        delay(100);
    }
    media = leitura / 10;
    
    // transforma a media de temperatura em string
    String tempe = String(media);
    
    //cria o objeto http, que posibilita acessar páginas da web
    HTTPClient http;

    // envia a requisicao http GET com a temperatura
    String url = "?temp=" + tempe;
    http.begin(client, servidor + url);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode = http.POST(url);
    if (httpCode == 200)
    {
        String retorno = http.getString();
        if(retorno == "0")
        {
          sendTelegramMessage();
        }
    }
    else
    {
        
    }
    //fecha a conexao
    http.end();
}

// envia mensagem para o bot do telegram
void sendTelegramMessage() {
  String message = "Temperatura Alta!";
  myBot.sendMessage(CHAT_ID, message);
}
