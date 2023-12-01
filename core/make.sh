#!/bin/bash
cd "$( dirname "${BASH_SOURCE[0]}" )"
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
