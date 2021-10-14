#include "ESP8266HTTPClient.h"
#include <ESP8266WiFi.h>
#include <DallasTemperature.h>
#include <WiFiClient.h>
#include <CTBot.h>
//Instancia os objetos
#define barramento 2 //Pino de sinal (amarelo) do DS18b20 ligado ao pino 4
String BOT_TOKEN = "2099943113:AAFIjD92tu3qia0v9wEQcQ2wt5xnEpMsy_w";
const int CHAT_ID = 789976451;
OneWire oneWire(barramento);
DallasTemperature ds18b20(&oneWire);
WiFiClient client; //cliente responsável pela conexão
CTBot myBot;
//Declaração das variáveis
const char* ssid = "MiMi"; 
const char* senha = "113126virus";
char servidor[] = "http://192.168.88.229/tcc2/enviaDados.php";//HTTPS não vai funcionar devido ao sertificado SSL

void setup() 
{
  Serial.begin(115200);
  WiFi.begin(ssid, senha);
  //permanece neste laço até a conexão
  while (WiFi.status() != WL_CONNECTED) 
  {
    delay(500); 
    Serial.print("."); 
  }
  ds18b20.begin();
  Serial.println("Inicializando TelegramBot...");

  // connect the ESP8266 to the desired access point
  myBot.wifiConnect(ssid, senha);

  // set the telegram bot token
  myBot.setTelegramToken(BOT_TOKEN);
  
  // check if all things are ok
  if (myBot.testConnection())
    Serial.println("\ntestConnection OK");
  else
    Serial.println("\ntestConnection NOK");
}

void loop() 
{
  TBMessage msg;
  Enviar();
  
  //espera por 15 minutos
  delay(90000);
  
}

void Enviar()
{
    double leitura = 0, media = 0;
    for (int i = 0; i < 10; i++)
    {
        ds18b20.requestTemperatures();
        leitura += ds18b20.getTempCByIndex(0);
        delay(100);
    }
    media = leitura / 10;
    String tempe = String(media);
    //cria o objeto http, que posibilita acessar páginas da web
    HTTPClient http;
    String url = "?temp=" + tempe;
    http.begin(client, servidor + url);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode = http.POST(url);//está POST pois se preiso usar GET, basta formar a URL, mas pode usar PORT, includive com GET também
    if (httpCode == 200)
    {
        //se cair aqui, deu tudo certo
        //retorno é a variável que retorna informações do PHP, qualquer coisa que você programar
        String retorno = http.getString();
        if(retorno == "0")
        {
          sendTelegramMessage();
        }
    }
    else
    {
        //se cair aqui, deu erro;
    }
    //fecha a conexão
    http.end();
}

void sendTelegramMessage() {
  String message = "Temperatura Alta !";
  myBot.sendMessage(CHAT_ID, message);
}
