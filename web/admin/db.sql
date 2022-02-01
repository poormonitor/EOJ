use jol;
delete from
    source_code
where
    solution_id not in (
        select
            solution_id
        from
            solution
    )
delete from
    runtimeinfo
where
    solution_id not in (
        select
            solution_id
        from
            solution
    )
delete from
    solution
where
    solution.contest_id = 0
    and solution.in_date < (
        select
            end_time
        from
            contest
        where
            contest_id =(
                select
                    contest_id
                from
                    contest_problem
                where
                    contest_problem.problem_id = solution.problem_id
            )
    )
)