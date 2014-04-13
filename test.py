#!/usr/bin/python

import time
from xpresser import Xpresser

def main():
    xp = Xpresser()
    xp.load_images('.')
    xp.click('12_1')
    time.sleep(1)
    xp.click('05_file')
    xp.click('06_export')
    xp.type('1.svg')
    xp.type(['<Enter>'])
    xp.click('05_file')
    xp.click('11_close')


if __name__ == "__main__":
    main()
