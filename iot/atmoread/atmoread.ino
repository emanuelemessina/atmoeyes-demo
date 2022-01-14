const int analogInPin = A0;

unsigned int sensorValue = 0;
unsigned int outputValue = 0;

void setup()
{
    Serial.begin(9600);
}

void loop()
{
    sensorValue = analogRead(analogInPin);
    outputValue = map(sensorValue, 0, 1023, 0, 100);

    char buffer[10];
    sprintf(buffer, "{\"pm10\":%u}", outputValue);
    Serial.println(buffer);
    
    delay(700);
}