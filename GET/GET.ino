// bibliotecas
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <DallasTemperature.h>
#include <OneWire.h>
#include <CTBot.h>
#include <ESP_Mail_Client.h>

//Instancia os objetos
#define barramento 2 //Pino de sinal (amarelo) do DS18b20 ligado ao pino 2
OneWire oneWire(barramento);
DallasTemperature ds18b20(&oneWire);
WiFiClient client; // cliente responsável pela conexao
CTBot myBot;

//Declaracao das variaveis
const char* ssid = "MiMi"; 
const char* senha = "113126virus";
char servidor[] = "http://192.168.88.229/vitoria/enviatemp.php";
String BOT_TOKEN = "2099943113:AAFIjD92tu3qia0v9wEQcQ2wt5xnEpMsy_w"; // token do telegram
const int CHAT_ID = 789976451; // id do telegram
#define SMTP_HOST "smtp.gmail.com"
#define SMTP_PORT 465
#define AUTHOR_EMAIL "nodemcu.alertatemp@gmail.com"
#define AUTHOR_PASSWORD "263111vivi"
SMTPSession smtp;
ESP_Mail_Session session;
SMTP_Message message;
void smtpCallback(SMTP_Status status);
String textMsg;
String tempe;

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
  smtp.debug(1);
  smtp.callback(smtpCallback);
  session.server.host_name = SMTP_HOST;
  session.server.port = SMTP_PORT;
  session.login.email = AUTHOR_EMAIL;
  session.login.password = AUTHOR_PASSWORD;
  message.sender.name = "NodeMCUESP8266";
  message.sender.email = AUTHOR_EMAIL;
  message.subject = "Alerta de Temperatura";
  message.addRecipient("Remetente", "vitoriarleonardo@gmail.com");
  message.text.charSet = "us-ascii";
  message.text.transfer_encoding = Content_Transfer_Encoding::enc_7bit;
  message.priority = esp_mail_smtp_priority::esp_mail_smtp_priority_high;
  message.response.notify = esp_mail_smtp_notify_success | esp_mail_smtp_notify_failure | esp_mail_smtp_notify_delay;
}

void loop() 
{
  enviar();
  delay(120000);
}

void enviar()
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
    tempe = String(media);
    
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
        if(retorno == "sim")
        {
          enviarMensagemTelegram();
          textMsg = textMsg + "A temperatura está fora do limite : " + tempe + " °C" + "\n";
          Serial.print(textMsg);
          Serial.println("Enviar e-mail");
          setTextMsg(); // define o e-mail
          enviaTextMsg(); // envia o e-mail
        }
    }
    else
    {
        
    }
    //fecha a conexao
    http.end();
}

// envia mensagem para o bot do telegram
void enviarMensagemTelegram() {
  String mensagem = "A temperatura está fora do limite estabelecido: " + tempe;
  myBot.sendMessage(CHAT_ID, mensagem);
}

void setTextMsg() {
  message.text.content = textMsg.c_str();
}

void enviaTextMsg() {
  message.addHeader("Message-ID: <vitoriarleonardo@gmail.com>");
  if (!smtp.connect(&session)) return;
  if (!MailClient.sendMail(&smtp, &message)) Serial.println("Erro ao enviar e-mail, " + smtp.errorReason());
}

void smtpCallback(SMTP_Status status)
{
  /* Print o status */
  Serial.println(status.info());

  /* Print o resultado do envio */
  if (status.success())
  {
    Serial.println("----------------");
    ESP_MAIL_PRINTF("Mensagem enviada com sucesso: %d\n", status.completedCount());
    ESP_MAIL_PRINTF("Mensagem com falha no envio: %d\n", status.failedCount());
    Serial.println("----------------\n");
    struct tm dt;

    for (size_t i = 0; i < smtp.sendingResult.size(); i++)
    {
      /* Pegar o item de resultado */
      SMTP_Result result = smtp.sendingResult.getItem(i);
      time_t ts = (time_t)result.timestamp;
      localtime_r(&ts, &dt);

      ESP_MAIL_PRINTF("Mensagem No: %d\n", i + 1);
      ESP_MAIL_PRINTF("Status: %s\n", result.completed ? "success" : "failed");
      ESP_MAIL_PRINTF("Data/Hora: %d/%d/%d %d:%d:%d\n", dt.tm_year + 1900, dt.tm_mon + 1, dt.tm_mday, dt.tm_hour, dt.tm_min, dt.tm_sec);
      ESP_MAIL_PRINTF("Conteudo: %s\n", result.recipients);
      ESP_MAIL_PRINTF("Assunto: %s\n", result.subject);
    }
    Serial.println("----------------\n");
    smtp.sendingResult.clear();
  }
}
