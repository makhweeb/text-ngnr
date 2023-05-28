import pandas as pd
data = pd.read_csv('filtered.csv', on_bad_lines='skip')

data.to_csv('filtered.csv', encoding='utf-8')