#! /usr/bin/python3

from copydetect import CopyDetector
from pprint import pprint
import sys
import os

file, pid = sys.argv[1], sys.argv[2]
DEBUG = len(sys.argv) > 3
full_path = "/home/judge/data/%s/ac/" % pid
get_sid = lambda x: os.path.splitext(os.path.basename(x))[0]
sim = 0

detector = CopyDetector(ref_dirs=[full_path], silent=not DEBUG, display_t=0.9)
detector.add_file(file, "test")

detector.run()

result = detector.get_copied_code_list()

if result:
    result = list(map(lambda x: x + [get_sid(x[3])], result))
    result.sort(key=lambda x: (sorted((x[0], x[1])), get_sid(x[3])), reverse=True)
    sim_s = result[0]
    sim = int(max(sim_s[0], sim_s[1]) * 100)
    sim_s_id = sim_s[-1]

    if DEBUG:
        pprint(list(map(lambda x: [x[-1], max(x[0], x[1])], result)))

    if sim == 100:
        detector_test = CopyDetector(disable_filtering=True, silent=not DEBUG)
        detector_test.add_file(file, type="test")
        detector_test.add_file(sim_s[3], type="ref")
        detector_test.run()

        result_text = detector_test.get_copied_code_list()
        if result_text and (result_text[0][0] == 1.0 or result_text[0][1] == 1.0):
            sim = 200

        if DEBUG and result_text:
            pprint([sim_s_id, max(result_text[0][:2])])

    if not DEBUG:
        with open("sim", "w") as fp:
            fp.write("%d %s" % (sim, sim_s_id))

exit(sim)
