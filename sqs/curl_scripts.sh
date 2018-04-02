#!/bin/bash
curl http://localhost/~jp/TAFE/sqs/ws/ws.php?getData=noInQueue
curl -d "description=asdfasdf&problem=asldfk" http://localhost/~jp/TAFE/sqs/ws/ws.php?getData=enqueue
