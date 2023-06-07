#!/bin/bash
cd judged
make
make install
make clean
cd ../judge_client
make
make install
make clean
cd ..
cp sim.py /usr/bin
chmod +x /usr/bin/sim.py
pip install copydetect
echo "Core part done!"
