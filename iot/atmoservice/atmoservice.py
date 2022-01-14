from http.client import HTTPException
from json.decoder import JSONDecodeError
import os
import signal
import sys
from time import sleep
from datetime import datetime
import serial
import requests
import json
import fnmatch
import logging
import traceback

baud = 9600
update_interval = 0.100
endpoint = 'https://emanuelemessina.altervista.org/uni/ssmic2021/atmoeyes/backend/data/aqi/send'
sensor_id = 70

def auto_detect_serial_unix(preferred_list=['*']):
    import glob
    glist = glob.glob('/dev/ttyUSB*') + glob.glob('/dev/ttyACM*')
    ret = []

    # try preferred ones first
    for d in glist:
        for preferred in preferred_list:
            if fnmatch.fnmatch(d, preferred):
                ret.append(d)
    if len(ret) > 0:
        return ret
    # now the rest
    for d in glist:
        ret.append(d)
    return ret

def killbill():
    logging.error(traceback.format_exc())
    os.killpg(os.getpid(), signal.SIGKILL)

while True:

    print("Trying to connect...")
    
    try:
        device = auto_detect_serial_unix()[0]

        s = serial.Serial(device,baud)
        s.reset_input_buffer()

        print(f"Serial Connection Enstablished - Device: {device}")

        while True:

            if s.in_waiting > 0:
                
                read = s.readline().decode('utf-8').rstrip()
                time_read = datetime.now()
                print(f"{time_read} ] Recieved: {read}\t", end='')

                try:
                    sensors_report = json.loads(read)
                    
                    # let's calculate AQI as the arithmetic mean of the sensor readings
                    aqi = 0
                    for sensor,value in sensors_report.items():
                        aqi += value
                    aqi /= len(sensors_report)
                    
                    data = {
                            'id': sensor_id,
                            'value': aqi
                        }

                    response = requests.post(endpoint, json = data)
                    
                    if response.status_code != 200:
                        raise HTTPException
                    print(f"+{int((datetime.now()-time_read).total_seconds()*1000)}ms Sent: {data}\t")
                
                except JSONDecodeError:
                    print(f"Malformed JSON!\n")
                    pass
                except HTTPException as e:
                    print(f"HTTP Error: {response.status_code}")
                    killbill()
                except Exception as e:
                    print("Exception while reading... ")                    
                    killbill()

            sleep(update_interval)
            
    except OSError:
        print("Serial Connection Lost, retrying in 5s ...")  
    except (FileNotFoundError, IndexError):
        print("Sensor not connected, retrying in 5s ...")  
    except Exception as e:
        print("Exception while connecting...")
        killbill()
    finally:
        sleep(5)
        continue
        